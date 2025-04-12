<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteController;

Route::get('/', fn() => view('landing-page'));
// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
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
    Route::view('/discover', 'pages.discover');
    Route::view('/matches', 'pages.recent-matches');
    Route::view('/profile', 'pages.profile');
});
