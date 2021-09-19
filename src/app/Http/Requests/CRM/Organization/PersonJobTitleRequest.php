<?php

namespace App\Http\Requests\CRM\Organization;

use Illuminate\Foundation\Http\FormRequest;

class PersonJobTitleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.person_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            '*.person_id.required' => 'The field is required.'
        ];
    }
}
