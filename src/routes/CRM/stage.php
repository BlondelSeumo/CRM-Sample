<?php

/**
 * This route file contains all
 * of @var App\Models\CRM\Pipeline\Pipeline
 * and @var \App\Models\CRM\Stage\Stage
 * related routes
 */

use App\Http\Controllers\CRM\Stage\StageController;

Route::resource('stages', StageController::class, ['names' => 'stages']);
