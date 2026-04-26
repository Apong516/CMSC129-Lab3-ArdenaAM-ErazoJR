<?php
use App\Http\Controllers\AIAssistantController;

Route::post('/ai/execute', [AIAssistantController::class, 'execute']);