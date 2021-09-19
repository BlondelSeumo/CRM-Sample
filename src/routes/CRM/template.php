<?php

    /**
     * This route file contains all
     * related routes
     */

    use App\Http\Controllers\CRM\Template\TemplateController;
    use Illuminate\Support\Facades\Route;

    Route::resource('templates', TemplateController::class);
