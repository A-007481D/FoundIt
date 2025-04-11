<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteController;

Route::get('/', function () {
  return view('landing-page');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::get('/login', function () {
   return view('auth.login');
})->name('login');

Route::get('/register', function () {
   return view('auth.register');
})->name('register');

Route::get('/discover', function () {
   return view('pages.discover');

});

Route::get('/matches', function () {
    return view('pages.recent-matches');
});


Route::get('/profile', function () {
    return view('pages.profile');
});



Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/facebook', [SocialiteController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);

Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);

Route::get('/reset-password', function () {
    return view('auth.reset-password');
})->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
