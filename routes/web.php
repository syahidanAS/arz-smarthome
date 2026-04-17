<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\CctvController;
use App\Http\Controllers\MqttController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    Route::get('/cctv', [CctvController::class, 'index']);
    Route::get('/cctv/stream', [CctvController::class, 'stream']);
    Route::get('/cctv/list', [CctvController::class, 'list']);
    Route::post('/switch-action', [WelcomeController::class, 'switchAction'])->name('switch-action');

    Route::post('publish', [MqttController::class, 'publish']);
    Route::get('/sensors', function () {
        return App\Models\SensorData::latest()->first();
    });

    Route::get('/cctv/file', function (\Illuminate\Http\Request $req) {
        $path = $req->get('path');

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/automations', [AutomationController::class, 'getData'])->name('automations');
    Route::post('/automations/toggle', [AutomationController::class, 'toggle'])->name('automations.toggle');
    Route::post('/automations/update', [AutomationController::class, 'update'])->name('automations.update');
    Route::post('/automation/store', [AutomationController::class, 'store'])->name('automation.store');
    Route::post('/automation/bulk-delete', [AutomationController::class, 'bulkDelete'])->name('automation.bulkDelete');
});
