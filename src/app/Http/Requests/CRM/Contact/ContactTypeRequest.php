<?php

namespace App\Http\Requests\CRM\Contact;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Contact\ContactType;
use Illuminate\Foundation\Http\FormRequest;

class ContactTypeRequest extends BaseRequest
{

    public function rules()
    {
        return $this->initRules(new ContactType());
    }
}
