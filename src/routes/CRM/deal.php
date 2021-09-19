<?php

    /**
     * This route file contains all.
     * @var \App\Models\CRM\Deal\Deal
     * @var \App\Models\CRM\Deal\LostReason
     * related routes
     */

    use App\Http\Controllers\CRM\Deal\DealController;
    use App\Http\Controllers\CRM\Deal\DealExportController;
    use App\Http\Controllers\CRM\Deal\LostReasonController;
    use App\Http\Controllers\CRM\Deal\SendDealPersonProposalController;
    use Illuminate\Support\Facades\Route;

    Route::group(['middleware' => 'public_access'], function () {
        Route::resource('deals', DealController::class);
    });



    Route::post('multiple/deals/move', [DealController::class, 'moveMultipleMarkedDeal'])
        ->name('multiple_deals.move');


    Route::get(
        'deal-by-person-org/{id}',
        [DealController::class, 'getDealByPersonOrg']
    )->name('deal.person_org');

    Route::group(['prefix' => 'deal'], function () {
        Route::get('/', [DealController::class, 'showDealByResource'])
            ->name('deal.deal_resource');

        /*
         * Deal Lost Reason Routes
         * As Lost Reason is only related with Deal
         * That's why we put all Models, Services, Controllers, Routes etc
         * Of Lost Reason in Deal directory
         */
        Route::resource('/lost-reasons', LostReasonController::class);
    });

    Route::get('export/deal', [DealExportController::class, 'export'])
        ->name('deal.export');
    Route::get('export/deal/{skip}', [DealExportController::class, 'download'])
    ->name('deal.export_skip');

// Send deal person proposal

    Route::post('send-deal-person-proposal', [SendDealPersonProposalController::class, 'dealProposal'])
        ->name('send_deal_person.proposal');
// Deal activities

    Route::post(
        'deal/activities/sync/{deal}',
        [DealController::class, 'dealActivitiesSync']
    )->name('deal.sync-activities');

    Route::post(
        'deal/followers/sync/{deal}',
        [DealController::class, 'dealFollowerSync']
    )->name('deal.sync-followers');

    Route::post(
        'deal/note/sync/{deal}',
        [DealController::class, 'dealNoteSync']
    )->name('deal.sync-note');


    Route::post(
        'deal/file/sync/{deal}',
        [DealController::class, 'dealFileSync']
    )->name('deal.sync-file');

    Route::get(
        'deal/activities/{deal}',
        [DealController::class, 'dealActivities']
    )->name('deal.view-activities');

    Route::put(
        'deal/person/delete/{deal}',
        [DealController::class, 'dealPersonDelete']
    )->name('deal.delete-person');

    Route::delete(
        'deal/organization/delete/{deal}',
        [DealController::class, 'dealOrganizationDelete']
    )->name('deal.delete-organization');

    Route::post(
        'deal/participants/sync/{deal}',
        [DealController::class, 'syncParticipants']
    )->name('deal.sync-participants');



    Route::get(
        'deals/{deal}/participants',
        [DealController::class, 'dealAllParticipants']
    )->name('deal_participants');
    Route::get(
        'deals/{deal}/collaborators',
        [DealController::class, 'dealAllCollaborators']
    )->name('deal_collaborators');

    Route::get(
        'deals/revision/{deal}',
        [DealController::class,'revision']
    )->name('deal.revision-history');

