<?php

use App\Http\Controllers\CRM\Contact\OrganizationController;
use App\Http\Controllers\CRM\Contact\PersonController;
use App\Http\Controllers\CRM\Deal\DealController;
use Illuminate\Support\Facades\Route;

Route::post('person-import', [PersonController::class, 'importPerson'])
    ->name('person.import-store');

Route::post('organization-import', [OrganizationController::class, 'importOrganization'])
    ->name('organization.import-store');

Route::post('deal-import', [DealController::class, 'importDeal'])
    ->name('deal.import-store');
