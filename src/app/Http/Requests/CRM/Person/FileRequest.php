<?php

namespace App\Http\Requests\CRM\Person;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'path.*' => 'file|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'path.*.max' => 'You can not upload a file larger than 5 MB.'
        ];
    }
}
