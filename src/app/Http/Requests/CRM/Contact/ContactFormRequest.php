<?php

namespace App\Http\Requests\CRM\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
            'phone.*.value' => 'required',
            'email.*.value' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'phone.*.value.required' => 'The phone field is required.',
            'email.*.value.required' => 'The email field is required.'
        ];
    }
}
