<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ATHDateTime\DayMonthController;
use App\Http\Controllers\ATHDateTime\ProvinceController;
use App\Http\Controllers\ATHDateTime\TimezoneController;


Route::get('/test', function () {
    $storage = Storage::get($this->file_path);
    dd($storage);
});






Route::get('/day-months', [DayMonthController::class, 'index'])->name('day_months.index');
Route::post('/day-months/days', [DayMonthController::class, 'updateDays'])->name('day_months.updateDays');
Route::post('/day-months/months', [DayMonthController::class, 'updateMonths'])->name('day_months.updateMonths');


Route::resource('timezones', TimezoneController::class);
Route::post('timezones/restore/{id}', [TimezoneController::class, 'restore'])->name('timezones.restore');
Route::delete('timezones/forceDelete/{id}', [TimezoneController::class, 'forceDelete'])->name('timezones.forceDelete');

Route::resource('provinces', ProvinceController::class);
Route::patch('/provinces/{id}/restore', [ProvinceController::class, 'restore'])->name('provinces.restore');
Route::delete('provinces/forceDelete/{id}', [ProvinceController::class, 'forceDelete'])->name('provinces.forceDelete');
