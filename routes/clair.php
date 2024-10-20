<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anthaleja\CLAIR\ChatController;
use App\Http\Controllers\Anthaleja\CLAIR\FeedbackController;

Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::post('chat/reset', [ChatController::class, 'resetConversation'])->name('chat.reset');
Route::get('chat/history', [ChatController::class, 'history'])->name('chat.history');
Route::get('chat/conversation/{conversation}', [ChatController::class, 'showConversation'])->name('chat.showConversation');

Route::post('feedback/{interaction}', [FeedbackController::class, 'store'])->name('feedback.store');
