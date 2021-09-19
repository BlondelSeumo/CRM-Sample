<?php

namespace App\Hooks\User;

use App\Hooks\HookContract;
use App\Helpers\Core\Traits\InstanceCreator;
use App\Models\Core\Auth\User;

class CustomRoute extends HookContract
{
    use InstanceCreator;

    public function handle(): array
    {
//        $user = User::with(['roles'])->where('id', auth()->id())->first();
//        if ($user->hasRole(['Agent'])) {
//            return [
//                'route_name' => 'persons.lists',
//                'route_params' => null
//            ];
//        } else if ($user->hasRole(['Client'])) {
//            return [
//                'route_name' => 'organizations.lists',
//                'route_params' => null
//            ];
//        }
        return [];
    }
}
