<?php

use App\Http\Controllers\JournalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\AIAssistantController; 

Route::get('/', function () {
    return redirect()->route('journals.index');
});

Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
Route::resource('journals', JournalController::class)->except(['show']);

Route::resource('users', UserController::class)->except(['show']);
Route::post('/users/switch/{id}', [UserController::class, 'switch'])->name('users.switch');

Route::put('journals/restore/{id}', [JournalController::class, 'restore'])->name('journals.restore');
Route::delete('journals/hard/{id}', [JournalController::class, 'hardDelete'])->name('journals.hardDelete');

Route::post('/chat', [ChatBotController::class, 'send']);
Route::post('/chat/crud', [ChatBotController::class, 'crud']);
Route::post('/ai/execute', [AIAssistantController::class, 'execute']); 

Route::prefix('api/journals')->group(function () {
    Route::get('/recent', [JournalApiController::class, 'recent'])->name('api.journals.recent');
    Route::get('/all', [JournalApiController::class, 'all'])->name('api.journals.all');
    Route::get('/count', [JournalApiController::class, 'count'])->name('api.journals.count');
    Route::get('/{id}', [JournalApiController::class, 'find'])->name('api.journals.find');
});