<?php

use Illuminate\Support\Facades\Route;

Route::post('/upload/audio', function (\Illuminate\Http\Request $request) {
    if ($request->hasFile('audio')) {
        $path = $request->file('audio')->store('audios', 'public');
        return response()->json([
            'message' => 'Audio uploaded successfully!',
            'path' => $path,
        ]);
    }
    return response()->json(['error' => 'No audio file provided'], 400);
});
Route::get('/upload/audio', function () {
    return response()->json(['message' => 'Use POST to upload audio']);
});

