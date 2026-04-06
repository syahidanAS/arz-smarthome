<?php

use App\Http\Controllers\CctvController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/cctv', [CctvController::class, 'index']);
Route::get('/cctv/stream', [CctvController::class, 'stream']);
Route::get('/cctv/list', [CctvController::class, 'list']);
Route::post('/switch-action', [WelcomeController::class, 'switchAction'])->name('switch-action');

Route::get('/cctv/file', function (\Illuminate\Http\Request $req) {
    $path = $req->get('path');

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});
