<?php

use App\Http\Controllers\ConversationAnalysisController;

Route::post('/conversation-analysis', [ConversationAnalysisController::class, 'store']);