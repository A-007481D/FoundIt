<?php

use Illuminate\Support\Facades\Route;

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
