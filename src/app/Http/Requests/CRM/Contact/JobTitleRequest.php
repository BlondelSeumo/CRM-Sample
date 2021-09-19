<?php

namespace App\Http\Requests\CRM\Contact;

use Illuminate\Foundation\Http\FormRequest;

class JobTitleRequest extends FormRequest
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
            '*.organization_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            '*.organization_id.required' => 'The field is required.'
        ];
    }
}
