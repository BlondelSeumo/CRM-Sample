<?php

namespace App\Http\Requests\CRM\Deal;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Deal\Deal;
use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends BaseRequest
{

    public function rules()
    {
        return $this->initRules(new Deal());
    }

    public function messages(){
        return [
            'contextable_id.required' => trans('validation.lead_field_is_required')
        ];
    }
}
