<?php

namespace App\Http\Requests\CRM\PipeLine;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Pipeline\Pipeline;


class PipelineRequest extends BaseRequest
{

    public function rules()
    {
       return $this->initRules(new Pipeline());
    }

    public function messages()
    {
        return ['stage.*.name.required' => trans('stage_name_is_required')];
    }
}
