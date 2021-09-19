<?php


namespace App\Services\CRM\Proposal;


use App\Models\CRM\Proposal\Proposal;
use App\Services\ApplicationBaseService;

class SendProposalService extends ApplicationBaseService
{
    public function __construct(Proposal $proposal)
    {
        $this->model = $proposal;
    }

}
