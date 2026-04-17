<?php

use App\Http\Controllers\TestSelectionController;
use App\Http\Controllers\UltrasonicTestController;
use App\Http\Controllers\UltrasonicAnalysisController;
use Illuminate\Support\Facades\Route;

// Home page - Test Selection
Route::get('/', [TestSelectionController::class, 'index'])->name('home');
Route::post('/select-test', [TestSelectionController::class, 'selectTest'])->name('test.select');

// Ultrasonic Test Routes (Input data pengujian) ← CUKUP SEKALI SAJA
Route::get('/ultrasonic/{idInspeksi}/create', [UltrasonicTestController::class, 'create'])
    ->name('ultrasonic.create');
Route::post('/ultrasonic/{idInspeksi}', [UltrasonicTestController::class, 'store'])
    ->name('ultrasonic.store');

// Halaman form analisis ultrasonic (MILIK ANDA)
Route::get('/ultrasonic-analysis', [UltrasonicAnalysisController::class, 'index'])
    ->name('ultrasonic.analysis.index');

// Proses analisis via AJAX (real-time)
Route::post('/ultrasonic-analyze', [UltrasonicAnalysisController::class, 'analyze'])
    ->name('ultrasonic.analysis.analyze');

// Simpan hasil analisis ke database
Route::post('/ultrasonic-store', [UltrasonicAnalysisController::class, 'store'])
    ->name('ultrasonic.analysis.store');

Route::get('/ultrasonic-analysis/result/{id}', [UltrasonicAnalysisController::class, 'result'])
    ->name('ultrasonic.analysis.result');