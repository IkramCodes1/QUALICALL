<?php

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login'])->middleware(\App\Http\Middleware\SetUserLocale::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
