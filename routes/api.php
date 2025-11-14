<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function() {
    Route::apiResource('blogs', BlogController::class)->middleware(['abilities:blog']);
    Route::apiResource('users', UserController::class)->middleware(['abilities:user']);

    Route::post('/signout', [AuthController::class, 'signout']);
});

Route::post('/signin', [AuthController::class, 'signin']);