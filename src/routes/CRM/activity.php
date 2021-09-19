<?php

/**
 * This route file contains all
 * @var \App\Models\CRM\Activity\
 * related routes
 */


use App\Http\Controllers\CRM\Activity\{
    ActivityController};

Route::resource('activities', ActivityController::class)->except('create', 'edit');


