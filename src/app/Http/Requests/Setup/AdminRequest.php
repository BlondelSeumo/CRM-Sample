<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'first_name' => 'required|min:1',
            'email' => 'required|email',
            'password' => ['required', 'min:8', 'regex:/^(?=[^\d]*\d)(?=[A-Z\d ]*[^A-Z\d ]).{8,}$/i'],
        ];
    }
}
