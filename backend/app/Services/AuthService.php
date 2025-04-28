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

class AuthService extends AuthRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
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
    public function login(array $data)
    {
        $user = $this->findByEmail($data['email']);

        // Distinguish between non-existent email and wrong password
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No user found with that email address.'],
            ]);
        }
        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Email is not verified.'],
            ]);
        }
        
        // Check if user is banned
        if ($user->status === 'banned') {
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
            } else {
                // Format the suspension end date if it exists
                $endDate = '';
                if ($user->suspension_end) {
                    // Make sure suspension_end is a Carbon instance
                    $endDate = ' until ' . Carbon::parse($user->suspension_end)->format('M d, Y');
                }
                
                $reason = $user->suspended_reason ? ': ' . $user->suspended_reason : '';
                throw ValidationException::withMessages([
                    'account' => ['Your account has been temporarily suspended' . $endDate . $reason],
                ]);
            }
        }

        return JWTAuth::fromUser($user);
    }
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
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
