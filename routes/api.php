<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyApiToken;
use App\Http\Controllers\Anthaleja\Wiki\ArticleController;
use App\Http\Controllers\Anthaleja\Api\FederationController;

Route::prefix('api')->middleware([VerifyApiToken::class])->group(function () {
    // SoNet Instances/Federations
    Route::post('/sonet/send-message', [FederationController::class, 'sendMessage'])->name('api.send-message');
    Route::post('/sonet/receive-message', [FederationController::class, 'receiveMessage'])->name('api.receive-message');
    Route::post('/sonet/post-content', [FederationController::class, 'postContent'])->name('api.post-content');
    Route::post('/sonet/receive-comment', [FederationController::class, 'receiveComment'])->name('api.receive-comment');
    // SoNet condivisione dei post e il recupero dei commenti
    Route::post('/sonet/share-post', [FederationController::class, 'sharePost'])->name('api.share-post');
    Route::post('/sonet/receive-post', [FederationController::class, 'receivePost'])->name('api.receive-post');
    Route::get('/sonet/post/{post_id}/comments', [FederationController::class, 'getPostComments'])->name('api.get-post-comments');


    // Wiki
    Route::get('/wiki/check-page', [ArticleController::class, 'checkPage']);
});
