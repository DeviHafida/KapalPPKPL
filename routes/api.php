<?php

use App\Http\Controllers\UltrasonicTestController;
use Illuminate\Support\Facades\Route;

Route::post('/ultrasonic/{idInspeksi}', [UltrasonicTestController::class, 'storeApi'])
    ->name('api.ultrasonic.store');
