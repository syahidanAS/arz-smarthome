<?php

use App\Http\Controllers\Api\RelayController;
use App\Http\Controllers\MqttController;
use Illuminate\Support\Facades\Route;

Route::post('/switch', [RelayController::class, 'index']);

Route::post('/publish', [MqttController::class, 'publish']);
