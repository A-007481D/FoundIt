<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function () {
    return response()->json([
        'success' => 'CORS works!',
        'data' => [
            'laravel_version' => app()->version(),
            'timestamp' => now()->toDateTimeString()
        ]
    ]);
});
