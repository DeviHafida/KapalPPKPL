<?php

use App\Http\Controllers\TestSelectionController;
use App\Http\Controllers\UltrasonicTestController;
use Illuminate\Support\Facades\Route;

// Home page - Test Selection
Route::get('/', [TestSelectionController::class, 'index'])->name('home');
Route::post('/select-test', [TestSelectionController::class, 'selectTest'])->name('test.select');

// Ultrasonic Test Routes
Route::get('/ultrasonic/{idInspeksi}/create', [UltrasonicTestController::class, 'create'])
    ->name('ultrasonic.create');
Route::post('/ultrasonic/{idInspeksi}', [UltrasonicTestController::class, 'store'])
    ->name('ultrasonic.store');
