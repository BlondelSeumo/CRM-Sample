<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Setup\Helper\PermissionsHelper;

class PermissionController extends Controller
{
    protected $helper;

    public function __construct(PermissionsHelper $helper)
    {
        $this->helper = $helper;
    }

    public function show()
    {
        return $this->helper->check();
    }
}
