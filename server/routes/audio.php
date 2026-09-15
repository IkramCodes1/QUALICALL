<?php
Route::get('/test-audio', function () {
    return asset('storage/audios/audio1.wav');
});