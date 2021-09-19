<?php

namespace App\Http\Requests\CRM\Activity;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Activity\ActivityType;

class ActivityTypeRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new ActivityType());
    }
}
