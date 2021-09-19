<?php

    /**
     * This route file contains all
     * related routes.
     */

    use App\Http\Controllers\CRM\Proposal\AddProposalController;
    use App\Http\Controllers\CRM\Proposal\ProposalController;
    use App\Http\Controllers\CRM\Proposal\SendProposalController;
    use Illuminate\Support\Facades\Route;

    Route::group(['middleware' => 'public_access'], function () {

        Route::resource('proposals', ProposalController::class);

    });

    Route::post('send-proposal', [SendProposalController::class, 'sendProposal'])
        ->name('proposals.send');


    Route::post('add-proposal', [AddProposalController::class, 'addProposal'])
        ->name('proposals.add');
