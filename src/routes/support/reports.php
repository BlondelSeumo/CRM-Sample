<?php

use App\Http\Controllers\CRM\Report\DealReportController;
use App\Http\Controllers\CRM\Report\PipelineReportController;
use App\Http\Controllers\CRM\Report\ProposalReportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reports'], function (){

    Route::get('proposals/chart', [ProposalReportController::class, 'chart'])
        ->name('proposals.view-chart')
        ->middleware('can:proposal_reports');

    Route::get('proposals/data-table', [ProposalReportController::class, 'dataTable'])
        ->name('proposals.view-data-table')
        ->middleware('can:proposal_reports');

    Route::get('proposal-details', [ProposalReportController::class, 'proposalReportDetails'])
        ->name('proposal-report-details')
        ->middleware('can:proposal_reports');

    //Pipeline report
    Route::get('pipeline/chart', [PipelineReportController::class, 'chart'])
        ->name('pipeline.view-chart')
        ->middleware('can:pipeline_reports');
    Route::get('pipeline/data-table', [PipelineReportController::class, 'dataTable'])
        ->name('pipeline.view-data-table')
        ->middleware('can:pipeline_reports');;
    Route::get('pipeline-details', [DealReportController::class, 'pipelineReportDetails'])
        ->name('pipeline-details')
        ->middleware('can:pipeline_reports');;
});

//Deal Report
Route::get(
    'deal-report',
    [DealReportController::class, 'dataTable']
)->name('deal.deal-report')->middleware('can:deal_reports');

Route::get(
    'deal-report-chart',
    [DealReportController::class, 'chart']
)->name('deal.deal-report-chart')->middleware('can:deal_reports');

Route::get(
    'deal-report-details',
    [DealReportController::class, 'dealReportDetails']
)->name('deal.deal-report-details')->middleware('can:deal_reports');

