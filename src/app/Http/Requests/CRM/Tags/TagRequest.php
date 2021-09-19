<?php

namespace App\Http\Requests\CRM\Tags;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Tag\Tag;
use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends BaseRequest
{



    public function rules()
    {
       return $this->initRules(new Tag());
    }
}
