<?php

use App\Http\Controllers\UserController;


Route::middleware(['auth:sanctum', \App\Http\Middleware\SetUserLocale::class, \App\Http\Middleware\UpdateLastActivity::class])->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::prefix('user')->group(function () {
        Route::get('/get', [UserController::class, 'get']);
        Route::post('/add', [UserController::class, 'add'])->middleware(\App\Http\Middleware\CheckPermission::class . ':user_add');
        Route::post('/delete', [UserController::class, 'delete'])->middleware(\App\Http\Middleware\CheckPermission::class . ':user_delete');
        Route::post('/update', [UserController::class, 'update'])->middleware(\App\Http\Middleware\CheckPermission::class . ':user_update');
    });
});
