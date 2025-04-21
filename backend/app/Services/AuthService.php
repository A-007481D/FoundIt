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

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Email is not verified.'],
            ]);
        }

        return JWTAuth::fromUser($user);
    }
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * @throws ValidationException
     */
    public function sendResetLink(array $data): true
    {
        $status = Password::sendResetLink($data);

        if($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => ['Could not send password reset link.'],
            ]);
        }
        return true;
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
