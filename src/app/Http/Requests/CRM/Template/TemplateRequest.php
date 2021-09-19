<?php

namespace App\Http\Requests\CRM\Template;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Template\Template;

class TemplateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->initRules(new Template());
    }
}
