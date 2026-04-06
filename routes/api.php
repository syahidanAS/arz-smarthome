<?php

use App\Http\Controllers\Api\RelayController;
use Illuminate\Support\Facades\Route;

Route::post('/switch', [RelayController::class, 'index']);
