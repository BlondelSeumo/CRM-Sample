<?php

namespace App\Http\Requests\CRM\Event;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Event\Event;

class EventRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new Event());
    }
}
