<?php

use App\Http\Controllers\CRM\Contact\ContactTypeController;
use App\Http\Controllers\CRM\Deal\DealController;
    use App\Http\Controllers\CRM\Frontend\FrontendController;
    use App\Http\Controllers\CRM\Stage\StageController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\CRM\Lang\LanguageController;

// Deal pipeline view

    Route::get('deal/pipeline-view/{pipeline_id?}', [DealController::class, 'showPipelineView'])
        ->name('deal.pipeline_view')
        ->middleware('public_access');

    Route::get('/deal/{id}/details', [FrontendController::class, 'dealDetails'])
        ->name('deal_details.page')->middleware('can:view_deals');

    Route::get('pipelines/list/view', [FrontendController::class, 'pipelineView'])
        ->name('pipelines.lists');
    Route::get('org/list', [FrontendController::class, 'organizationList'])
        ->name('organizations.lists');

    Route::get('person/list', [FrontendController::class, 'personView'])
        ->name('persons.lists');
    Route::get('deals/list/view', [FrontendController::class, 'dealsListView'])
        ->name('deals.lists');
//Route::get('/deals', [DealController::class, 'index']);
//Route::get('/organizations', [OrganizationController::class, 'index']);
//Route::get('/persons', [PersonController::class, 'index']);
    Route::get('/stages', [StageController::class, 'index'])->name('stages.index');
    Route::get('contact/type/list', [FrontendController::class, 'contactTypeList'])
        ->name('contact_type.list');
//Route::resource('contact/types', ContactTypeController::class)->only('index');

    Route::get('template/view', [FrontendController::class, 'templateView'])
        ->name('template.view')
        ->middleware('can:view_templates');
    Route::get('lang', [LanguageController::class, 'index'])
    ->name('lang.index');
    Route::post('lang', [LanguageController::class, 'store'])
    ->name('lang.store');

Route::get('get-lead-group', [ContactTypeController::class, 'getLeadGroup'])
    ->name('get_lead_groups');
