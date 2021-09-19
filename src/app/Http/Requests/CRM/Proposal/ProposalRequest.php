<?php

namespace App\Http\Requests\CRM\Proposal;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Proposal\Proposal;

class ProposalRequest extends BaseRequest
{

    public function rules()
    {
        return $this->initRules(new Proposal());
    }
}
