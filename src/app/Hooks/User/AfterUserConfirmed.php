<?php


namespace App\Hooks\User;


use App\Helpers\Core\Traits\InstanceCreator;
use App\Hooks\HookContract;

class AfterUserConfirmed extends HookContract
{
    use InstanceCreator;

    public function handle()
    {
        $lead = $this->model
            ->join('people', 'users.id', '=', 'people.attach_login_user_id')
            ->where('users.invitation_token', request()->get('invitation_token'));
        return $lead->count() > 0 ?
            $lead->update([
                'name' => request()->get('first_name') . ' ' . request()->get('last_name')
            ]) : $this->model;

    }
}
