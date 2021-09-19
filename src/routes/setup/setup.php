<?php

use App\Http\Controllers\Core\Setting\DeliverySettingController;
use App\Http\Controllers\CRM\Setting\BroadcastSettingController;
use App\Http\Controllers\Setup\EnvironmentController;
use Illuminate\Support\Facades\Route;

Route::get('environment', [EnvironmentController::class, 'index'])
    ->name('environment.index');

Route::post('environment', [EnvironmentController::class, 'store'])
    ->name('environment.store');

Route::get('admin-info', [EnvironmentController::class, 'show'])
    ->name('environment.admin-info');

Route::get('email-setup', [EnvironmentController::class, 'emailSetup'])
    ->name('environment.email-setup');

Route::post('email-setting-update-delivery', [DeliverySettingController::class, 'update'])
    ->name('environment.email-setting-update-delivery');

Route::get('broadcast-setup', [EnvironmentController::class, 'broadcastSetup'])
    ->name('environment.broadcast-setup');

Route::post('broadcast-setting-update', [BroadcastSettingController::class, 'update'])
    ->name('environment.broadcast-setting-update');

Route::post('install', [EnvironmentController::class, 'update'])
    ->name('environment.install');

