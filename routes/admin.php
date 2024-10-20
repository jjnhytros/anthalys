<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anthaleja\Admin\ResourceController;
use App\Http\Controllers\Anthaleja\Admin\DashboardController;

Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/admin/resources', [ResourceController::class, 'index'])->name('admin.resources.index');
Route::post('/admin/resources/update', [ResourceController::class, 'update'])->name('admin.resources.update');
