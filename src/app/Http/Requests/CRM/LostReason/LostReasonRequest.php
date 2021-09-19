<?php

namespace App\Http\Requests\CRM\LostReason;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Deal\LostReason;

class LostReasonRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new LostReason());
    }
}
