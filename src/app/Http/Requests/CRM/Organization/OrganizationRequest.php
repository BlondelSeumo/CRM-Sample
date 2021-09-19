<?php

namespace App\Http\Requests\CRM\Organization;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Organization\Organization;


class OrganizationRequest extends BaseRequest
{


    public function rules()
    {
        return $this->initRules(new Organization());
    }
    public function messages()
    {
        return [
            'contact_type_id.required' => 'The lead group field is required.'
        ];
    }
}
