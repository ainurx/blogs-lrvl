<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function() {
    Route::get('/blogs', [BlogController::class, 'index']);

    Route::post('/blog', [BlogController::class, 'store'])->withoutMiddleware('web');

    Route::post('/signin', [AuthController::class, 'signin'])->withoutMiddleware('web');
    Route::post('/admin', [AuthController::class, 'signup'])->withoutMiddleware('web');
});