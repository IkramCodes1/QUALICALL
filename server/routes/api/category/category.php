<?php

use App\Http\Controllers\CategoryController;


Route::middleware(['auth:sanctum', \App\Http\Middleware\SetUserLocale::class, \App\Http\Middleware\UpdateLastActivity::class])->group(function () {
    Route::get('/category', [CategoryController::class, 'user']);
    Route::prefix('category')->group(function () {
        Route::get('get', [CategoryController::class, 'get']);
        Route::post('add', [CategoryController::class, 'add']);
        Route::post('update', [CategoryController::class, 'update']);
        Route::post('delete', [CategoryController::class, 'delete']);
    });
});
