<?php

/**
 * This route file contains all
 * of @var App\Models\CRM\Pipeline\Pipeline
 * and @var \App\Models\CRM\Stage\Stage
 * related routes
 */

use App\Http\Controllers\CRM\Pipeline\PipelineController;

Route::group(['middleware' => 'public_access'], function () {
    Route::resource('pipelines', PipelineController::class);
});

Route::post('pipeline-import', [PipelineController::class, 'pipelineImport'])->name('pipeline.import');
