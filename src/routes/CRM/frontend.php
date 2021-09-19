<?php

use App\Http\Controllers\CRM\Frontend\FrontendController;

// Use this route to define all view / blade for our frontend
// And use FrontendController

/**
 * @var FrontendController
 */


// Route::get('org/list', [FrontendController::class, 'organizationList'])
//       ->name('organizations.lists');

// Route::get('person/list', [FrontendController::class, 'personView'])
//     ->name('persons.lists');

Route::get('/{person_name}/details', [FrontendController::class, 'personDetails'])
    ->name('persons.details');

Route::get('/person/import', [FrontendController::class, 'personImport'])
    ->name('persons.import');

Route::get('organization/import', [FrontendController::class, 'organizationImport'])
    ->name('organization.import');

Route::get('contact/type/list', [FrontendController::class, 'contactTypeList'])
    ->name('contact_type.list');

// Route::get('deals/list/view', [FrontendController::class, 'dealsListView'])
//     ->name('deals.lists');

Route::get('/deals/pipeline/view', [FrontendController::class, 'dealsPipelineView'])
    ->name('deals_pipeline.page');

Route::get('lost/reasons/list/view', [FrontendController::class, 'lostReasonListView'])
    ->name('lost_reasons.lists');

Route::get('proposals/list/view', [FrontendController::class, 'proposalsListView'])
    ->name('proposals.lists');

Route::get('proposals/{id}/copy', [FrontendController::class, 'proposalCopy'])
    ->name('proposals.copy');

Route::get('templates/{id}/copy', [FrontendController::class, 'templateCopy'])
    ->name('templates.copy');

Route::get('users/list', [FrontendController::class, 'userList'])
    ->name('users.lists');

Route::get('activities/list/view', [FrontendController::class, 'activityListView'])
    ->name('activities.lists');

// Route::get('pipelines/list/view', [FrontendController::class, 'pipelineView'])
//     ->name('pipelines.lists');

Route::get('import/pipeline', [FrontendController::class, 'importPipelineView'])
    ->name('import_pipeline.view');

Route::get('settings/page', [FrontendController::class, 'settingPages'])
    ->name('settings.page');

Route::get('/add-edit-pipeline', [FrontendController::class, 'addEditPipeline'])
    ->name('add_edit_pipeline.page');

Route::get('deal/import', [FrontendController::class, 'dealImport'])
    ->name('deal.import');

Route::get('/reports/deal', [FrontendController::class, 'reportsDeal'])
    ->name('reports.deal');

Route::get('/reports/proposal', [FrontendController::class, 'reportsProposal'])
    ->name('reports.proposal');

Route::get('/reports/pipeline', [FrontendController::class, 'reportsPipeline'])
    ->name('reports.pipeline');

Route::get('notifications/list', [FrontendController::class, 'notificationList'])
    ->name('notifications.list');
