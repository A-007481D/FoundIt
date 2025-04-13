<?php

namespace App\Http\Controllers;

use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthenticationService $authService;


    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8', /// TODO: ADD CONFIRMED PW LATER
        ]);
        $user = $this->authService->register($validated);
        return redirect()->route('login')->with('status', 'Registration successful! Please check your email for the verification link.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $token = $this->authService->login($validated);
            Auth::loginUsingId($this->authService->findByEmail($validated['email'])->id);
            return redirect()->route('discover')->with('status', 'Logged in successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
    public function logout(Request $request)
    {
        try {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $this->authService->logout();
            return redirect()->route('login')
                ->with('status', 'Logged out successfully.')
                ->withoutCookie('XSRF-TOKEN')
                ->withoutCookie('foundit_session');

        } catch (\RuntimeException $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->authService->sendResetLink($validated);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        return redirect()->back()->with('status', 'Password reset link sent.');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        try {
            $this->authService->resetPassword($validated);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
}
