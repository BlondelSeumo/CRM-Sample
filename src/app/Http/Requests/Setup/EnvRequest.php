<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Core\Traits\PasswordMessageHelper;

class EnvRequest extends FormRequest
{
    use PasswordMessageHelper;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'database_connection' => 'required|in:mysql,pgsql,sqlsrv',
            'database_hostname' => 'required|min:3',
            'database_port' => 'required|min:3',
            'database_name' => 'required',
            'database_username' => 'required',
            'database_password' => 'nullable',
            'code' => 'required|min:3',
        ];
    }
}
