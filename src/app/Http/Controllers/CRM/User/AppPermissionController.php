<?php

namespace App\Http\Controllers\CRM\User;

use App\Filters\Common\Auth\PermissionFilter;
use App\Filters\Core\BaseFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AppPermissionController extends Controller
{
    public function __construct(BaseFilter $filter)
    {
        $this->filter = $filter;
    }
    public function index()
    {
        return PermissionFilter::new(true, Permission::query()
            ->filters($this->filter))
            ->filter()
            ->whereNotIn('group_name', ['clients', 'public_access_behavior'])
            ->get()
            ->when(!request()->without_group, function (Collection $query) {
                return $query->groupBy(function ($permission) {
                    return $permission->group_name;
                });
            });
    }
}
