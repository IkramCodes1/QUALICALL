<?php

use App\Http\Controllers\ConversationController;

Route::middleware([
    'auth:sanctum',
    \App\Http\Middleware\SetUserLocale::class,
    \App\Http\Middleware\UpdateLastActivity::class
])->group(function () {

    Route::prefix('conversation')->group(function () {


        

        Route::post('/delete/{id}', [ConversationController::class, 'destroy'])
            ->middleware(\App\Http\Middleware\CheckPermission::class . ':conversation_delete');

    });
    
});


Route::prefix('conversation')->group(function () {

    Route::get('/get', [ConversationController::class, 'index']);
    Route::get('/show/{id}', [ConversationController::class, 'show']);
    Route::post('/add', [ConversationController::class, 'store']);
    Route::post('/conversation/add', [ConversationController::class, 'store']);

});