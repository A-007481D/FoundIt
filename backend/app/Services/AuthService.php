<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Repositories\AuthRepository;
use Illuminate\Auth\Events\Registered;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Notifications\ResetPasswordNotification;
use App\Services\UserSessionService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AuthService extends AuthRepository
{
    protected UserSessionService $sessionService;
    protected ActivityLogService $activityLogService;
    
    /**
     * Create a new class instance.
     */
    public function __construct(UserSessionService $sessionService = null, ActivityLogService $activityLogService = null)
    {
        $this->sessionService = $sessionService ?? new UserSessionService();
        $this->activityLogService = $activityLogService ?? new ActivityLogService();
    }

    public function register(array $data)
    {
        $user = $this->create($data);
        event(new Registered($user));

        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function login(array $data, Request $request = null)
    {
        $user = $this->findByEmail($data['email']);

        // Distinguish between non-existent email and wrong password
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No user found with that email address.'],
            ]);
        }
        if (!Hash::check($data['password'], $user->password)) {
            // Log failed login attempt
            if ($request && $user && $user->id) {
                $this->activityLogService->log(
                    'login_failed',
                    'User',
                    $user->id,
                    ['reason' => 'incorrect_password', 'user_id' => $user->id],
                    $user->id,
                    'authentication',
                    $request
                );
            }
            
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        if (!$user->hasVerifiedEmail()) {
            // Log failed login attempt
            if ($request && $user && $user->id) {
                $this->activityLogService->log(
                    'login_failed',
                    'User',
                    $user->id,
                    ['reason' => 'email_not_verified', 'user_id' => $user->id],
                    $user->id,
                    'authentication',
                    $request
                );
            }
            
            throw ValidationException::withMessages([
                'email' => ['Email is not verified.'],
            ]);
        }
        
        // Check if user is banned
        if ($user->status === 'banned') {
            // Log failed login attempt
            if ($request && $user && $user->id) {
                $this->activityLogService->log(
                    'login_failed',
                    'User',
                    $user->id,
                    ['reason' => 'account_banned', 'banned_reason' => $user->banned_reason, 'user_id' => $user->id],
                    $user->id,
                    'authentication',
                    $request
                );
            }
            
            $reason = $user->banned_reason ? ': ' . $user->banned_reason : '';
            throw ValidationException::withMessages([
                'account' => ['Your account has been banned' . $reason],
            ]);
        }
        
        // Check if user is suspended
        if ($user->status === 'suspended') {
            // Check if suspension period is over
            if ($user->suspension_end && now()->greaterThan($user->suspension_end)) {
                // Reactivate user if suspension period is over
                $user->status = 'active';
                $user->save();
                
                // Log user reactivation
                if ($request && $user && $user->id) {
                    $this->activityLogService->log(
                        'account_reactivated',
                        'User',
                        $user->id,
                        ['reason' => 'suspension_expired', 'user_id' => $user->id],
                        $request
                    );
                }
            } else {
                // Format the suspension end date if it exists
                $endDate = '';
                if ($user->suspension_end) {
                    // Make sure suspension_end is a Carbon instance
                    $endDate = ' until ' . Carbon::parse($user->suspension_end)->format('M d, Y');
                }
                
                // Log failed login attempt
                if ($request && $user && $user->id) {
                    $this->activityLogService->log(
                        'login_failed',
                        'User',
                        $user->id,
                        [
                            'reason' => 'account_suspended', 
                            'suspended_reason' => $user->suspended_reason,
                            'suspension_end' => $user->suspension_end,
                            'user_id' => $user->id
                        ],
                        $user->id,
                        'authentication',
                        $request
                    );
                }
                
                $reason = $user->suspended_reason ? ': ' . $user->suspended_reason : '';
                throw ValidationException::withMessages([
                    'account' => ['Your account has been temporarily suspended' . $endDate . $reason],
                ]);
            }
        }

        // Generate JWT token
        $token = JWTAuth::fromUser($user);
        
        // Create session record
        if ($request && $user && $user->id) {
            // Create session first
            $this->sessionService->createSession($user->id, $token, $request);
            
            // Log successful login with explicit user_id
            $this->activityLogService->log(
                'login',
                'User',
                $user->id,
                ['user_id' => $user->id],  // Explicitly include user_id in metadata
                $user->id,   // Explicitly provide user_id as 5th parameter
                'authentication', // Add category as 6th parameter
                $request    // Request is now 7th parameter
            );
        }
        
        return $token;
    }
    public function logout(Request $request = null): void
    {
        $token = JWTAuth::getToken();
        $user = auth('api')->user();
        
        if ($token && $user && $request) {
            // Find and terminate the session
            $hashedToken = hash('sha256', $token->get());
            $session = $user->sessions()->where('token', $hashedToken)->first();
            
            if ($session) {
                $session->terminate();
            }
            
            // Log the logout activity
            $this->activityLogService->log(
                'logout',
                'User',
                $user->id,
                ['user_id' => $user->id],
                $user->id,
                'authentication',
                $request
            );
        }
        
        // Invalidate the token
        if ($token) {
            JWTAuth::invalidate($token);
        }
    }

    /**
     * Generate a reset token, send notification, and return the token
     *
     * @throws ValidationException
     */
    public function sendResetLink(array $data): string
    {
        $user = $this->findByEmail($data['email']);
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['No user found with that email address.'],
            ]);
        }
        // generate token and persist to password_reset_tokens table
        $token = Password::broker()->createToken($user);
        // send notification via custom class
        $user->notify(new ResetPasswordNotification($token));
        return $token;
    }

    public function resetPassword(array $data): true
    {
        $status = Password::reset(
            $data,
            function ($userModel, string $password) {
                DB::table('users')
                    ->where('id', $userModel->id)
                    ->update([
                        'password' => Hash::make($password)
                    ]);
                $userModel->remember_token = Str::random(60);
                event(new PasswordReset($userModel));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => ['Reset failed. Try again.'],
            ]);
        }

        return true;
    }
}
