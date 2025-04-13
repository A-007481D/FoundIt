<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', fn() => view('landing-page'));

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::find($id);

    if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link');
    }

    $user->markEmailAsVerified();
    return redirect('/profile')->with('verified', true);
})->middleware(['signed'])->name('verification.verify');
// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');
// password resets
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::get('/reset-password', fn() => view('auth.reset-password'))->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
// socialite auth
Route::get('/auth/facebook', [SocialiteController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
// protected
Route::middleware('auth')->group(function () {
    // email verification notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // resend verification
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');

    // verified oonly
    Route::middleware('verified')->group(function () {
        Route::view('/discover', 'pages.discover')->name('discover');
        Route::view('/matches', 'pages.recent-matches');
        Route::view('/settings', 'pages.profile');
    });
});

// test email
Route::get('/send-test-email', function () {
    Mail::raw('This is a test email!', function ($message) {
        $message->to('labidabdelmalek@gmail.com')
            ->subject('Test Email from Laravel');
    });
    return 'Test email sent!';
});
//// Email verification routes (Moved OUTSIDE auth middleware)
//Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//    $request->fulfill();
//    return redirect('/profile'); // Redirect after verification
//})->middleware(['signed'])->name('verification.verify');

