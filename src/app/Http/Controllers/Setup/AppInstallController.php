<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Setup\Helper\PermissionsHelper;
use App\Setup\Helper\Requirements;

class AppInstallController extends Controller
{
    protected $requirements;

    protected $permission;

    public function __construct(Requirements $requirements, PermissionsHelper $permission)
    {
        $this->requirements = $requirements;
        $this->permission = $permission;
    }

    public function index()
    {
        if ($this->requirements->isSupported() && $this->permission->isSupported()) {
            return redirect()->route('app.environment');
        }
        return view('install.index');
    }

    public function show()
    {
        return array_merge(array_merge([
            'php' => $this->requirements->checkPhpVersion()
        ], $this->requirements->check()), [
            'permissions' => $this->permission->check()
        ] );
    }
}
