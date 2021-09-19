<?php

namespace App\Http\Requests\CRM\Import;

use Illuminate\Foundation\Http\FormRequest;

class ImportOrganizationRequest extends FormRequest
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
            'import_file' => 'file|mimes:csv,txt|required'
        ];
    }
}
