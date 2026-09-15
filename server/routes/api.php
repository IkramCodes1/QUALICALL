<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use Illuminate\Http\Request;
use App\Models\Conversation;


require __DIR__.'/api/auth/auth.php';
require __DIR__.'/api/user/user.php';
require __DIR__.'/api/category/category.php';
require __DIR__.'/api/conversation/conversation.php';
require __DIR__.'/analyseConv.php';



Route::get('/audio/{file}', function ($file) {
    $path = storage_path('app/public/audios/' . $file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Access-Control-Allow-Origin' => '*'
    ]);
});


Route::post('/upload/audio', function (Request $request) {

    if ($request->hasFile('audio')) {

        // upload file
        $path = $request->file('audio')->store('audios', 'public');

        // save database
        $conversation = Conversation::create([

            'name' => $request->name,

            'path' => $path,

            'duration' => $request->duration,

            'call_date' => $request->call_date,

            'societe_id' => $request->societe_id,

            'is_transcripted' => 0,

            'is_evaluate' => 0,
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation
        ]);
    }

    return response()->json([
        'error' => 'No audio file'
    ], 400);
});

Route::post(
    '/conversations/{id}/generate',
    [ConversationController::class, 'generate']
);