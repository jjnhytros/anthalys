<?php
// routes/sonet.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anthaleja\SoNet\AdController;
use App\Http\Controllers\Anthaleja\SoNet\SonetController;
use App\Http\Controllers\Anthaleja\SoNet\CommentController;
use App\Http\Controllers\Anthaleja\SoNet\LuminumController;
use App\Http\Controllers\Anthaleja\SoNet\DonationController;
use App\Http\Controllers\Anthaleja\SoNet\JobOfferController;
use App\Http\Controllers\Anthaleja\SoNet\JobReviewController;
use App\Http\Controllers\Anthaleja\SoNet\PortfolioController;
use App\Http\Controllers\Anthaleja\SoNet\SonetPostController;
use App\Http\Controllers\Anthaleja\SoNet\SonetRoomController;
use App\Http\Controllers\Anthaleja\SoNet\ContentSaleController;
use App\Http\Controllers\Anthaleja\SoNet\SonetReportController;
use App\Http\Controllers\Anthaleja\SoNet\SubscriptionController;
use App\Http\Controllers\Anthaleja\SoNet\SonetConnectionController;
use App\Http\Controllers\Anthaleja\SoNet\SonetRoomMessageController;

/**
 * SoNet
 */

Route::prefix('/sonet')->group(function () {
    Route::prefix('donations')->group(function () {
        Route::post('/send', [DonationController::class, 'send'])->name('donations.send');
    });

    Route::prefix('/sonet/lumina')->name('lumina.')->group(function () {
        Route::post('/add', [LuminumController::class, 'addLuminum'])->name('add');
        Route::post('/remove', [LuminumController::class, 'removeLuminum'])->name('remove');
    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [SonetPostController::class, 'index'])->name('index');
        Route::post('/store-or-update', [SonetPostController::class, 'storeOrUpdate'])->name('storeOrUpdate');
        Route::delete('/{id}', [SonetPostController::class, 'destroy'])->name('destroy');
        Route::get('/posts/load-more', [SonetPostController::class, 'loadMore'])->name('posts.loadMore');
    });

    Route::prefix('subscriptions')->group(function () {
        Route::post('/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/complete', [SubscriptionController::class, 'completeSubscription'])->name('subscriptions.complete');
        Route::post('/confirm-renewal/{subscription}', [SubscriptionController::class, 'confirmRenewal'])->name('subscriptions.confirmRenewal');
        Route::get('/check-expirations', [SubscriptionController::class, 'checkExpirations'])->name('subscriptions.checkExpirations');
    });

    Route::prefix('connections')->name('connections.')->group(function () {
        Route::post('/', [SonetConnectionController::class, 'store'])->name('store');
        Route::delete('/{id}', [SonetConnectionController::class, 'destroy'])->name('destroy');
        Route::put('/{id}', [SonetConnectionController::class, 'update'])->name('update');
        Route::get('/requests', [SonetConnectionController::class, 'index'])->name('index');
    });

    Route::prefix('content-sales')->group(function () {
        Route::post('/process', [ContentSaleController::class, 'processSale'])->name('content-sales.process');
    });

    Route::prefix('ads')->group(function () {
        Route::post('{id}/interact/{interactionType}', [AdController::class, 'interact'])->name('ads.interact');
    });

    Route::prefix('/reports')->name('sonet_reports.')->group(function () {
        Route::post('/store', [SonetReportController::class, 'store'])->name('store');
        // Altre route per gestire le segnalazioni
    });

    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [SonetRoomController::class, 'index'])->name('index');
        Route::get('/create', [SonetRoomController::class, 'create'])->name('create');
        Route::post('/', [SonetRoomController::class, 'store'])->name('store');
        Route::get('/{room}', [SonetRoomController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SonetRoomController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SonetRoomController::class, 'update'])->name('update');
        Route::delete('/{id}', [SonetRoomController::class, 'destroy'])->name('destroy');

        Route::middleware(['auth', 'check.room.role:admin'])->group(function () {
            Route::post('/{id}/members', [SonetRoomController::class, 'addMember'])->name('addMember');
            Route::delete('/{id}/members/{memberId}', [SonetRoomController::class, 'removeMember'])->name('removeMember');
            Route::put('/{id}/members/role', [SonetRoomController::class, 'updateMemberRole'])->name('members.updateRole');
        });

        Route::prefix('/{room}/messages')->name('messages.')->middleware(['auth', 'check.room.role:moderator'])->group(function () {
            Route::post('/', [SonetRoomMessageController::class, 'store'])->name('store');
            Route::get('/{message}/edit', [SonetRoomMessageController::class, 'edit'])->name('edit');
            Route::put('/{message}', [SonetRoomMessageController::class, 'update'])->name('update');
            Route::delete('/{message}', [SonetRoomMessageController::class, 'destroy'])->name('destroy');
        });
    });



    Route::get('/timeline', [SonetController::class, 'index'])->name('timeline');
    Route::post('/sonets', [SonetController::class, 'store'])->name('anthaleja.sonet.sonets.store');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::prefix('job_offers')->group(function () {
        Route::get('/', [JobOfferController::class, 'index'])->name('job_offers.index');
        Route::get('/create', [JobOfferController::class, 'create'])->name('job_offers.create');
        Route::post('/', [JobOfferController::class, 'store'])->name('job_offers.store');
        Route::get('/recommendations', [JobOfferController::class, 'recommendations'])->name('job_offers.recommendations');
        Route::post('/{jobOffer}/reviews', [JobReviewController::class, 'store'])->name('job_reviews.store');
        Route::get('/search', [JobOfferController::class, 'search'])->name('job_offers.search');
    });

    Route::prefix('portfolios')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('portfolios.index');
        Route::post('/', [PortfolioController::class, 'store'])->name('portfolios.store');
        Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
    });
});
