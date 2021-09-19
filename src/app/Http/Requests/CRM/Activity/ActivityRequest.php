<?php

namespace App\Http\Requests\CRM\Activity;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Activity\Activity;

class ActivityRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new Activity());
    }
    public function messages(){
        return [
            'start_time.date_format' => trans('validation.the_start_time_is_not_a_valid_time'),
            'end_time.date_format' => trans('validation.the_end_time_is_not_a_valid_time')
        ];
    }
}
