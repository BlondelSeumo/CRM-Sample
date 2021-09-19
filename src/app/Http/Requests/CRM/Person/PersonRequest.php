<?php

namespace App\Http\Requests\CRM\Person;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Person\Person;

class PersonRequest extends BaseRequest
{
    protected $phone;

    public function rules()
    {
        return $this->initRules(new Person());
    }

    public function messages()
    {
        return [
            'contact_type_id.required' => 'The lead group field is required.'
        ];
    }
}
