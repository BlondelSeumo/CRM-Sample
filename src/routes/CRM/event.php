<?php

use App\Http\Controllers\CRM\Event\EventController;

Route::resource('events', EventController::class);


Route::get('user-activity/{id}', [\App\Http\Controllers\CRM\User\AppUserController::class, 'userActivities']);
