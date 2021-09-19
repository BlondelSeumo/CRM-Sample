<?php

namespace App\Http\Controllers\CRM\Proposal;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Proposal\ProposalRequest as Request;
use App\Services\CRM\Proposal\ProposalService;

class AddProposalController extends Controller
{
    public function __construct(ProposalService $proposalService)
    {
        $this->service = $proposalService;
    }

    public function addProposal(Request $request)
    {
        $this->service->save();
        return created_responses('proposal');
    }
}
