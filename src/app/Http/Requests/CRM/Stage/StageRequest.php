<?php

namespace App\Http\Requests\CRM\Stage;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Stage\Stage;

class StageRequest extends BaseRequest
{


    public function rules()
    {
        return $this->initRules(new Stage());
    }
}
