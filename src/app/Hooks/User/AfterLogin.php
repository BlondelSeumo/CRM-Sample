<?php

namespace App\Hooks\User;

use App\Hooks\HookContract;
use App\Helpers\Core\Traits\InstanceCreator;

class AfterLogin extends HookContract
{
    use InstanceCreator;

    public function handle()
    {
        cache()->forget('user-'.$this->model->id);
        cache()->forget('user-roles-permissions-'.$this->model->id);
        cache()->forget('user-roles-'.$this->model->id);
        cache()->forget('auth-user-permissions-'.$this->model->id);

        return $this->model;
    }
}
