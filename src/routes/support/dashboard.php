<?php

use App\Http\Controllers\CRM\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'public_access'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('deal-overview', [DashboardController::class, 'dealOverView'])->name('deal.overview');
});
