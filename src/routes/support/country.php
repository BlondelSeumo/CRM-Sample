<?php

use App\Http\Controllers\CRM\Country\CountryController;

Route::get(
    'country',
    [CountryController::class, 'index']
)->name('countries');