<?php


namespace App\Services\CRM\Contact;


use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Services\ApplicationBaseService;

class PersonInviteLeadService extends ApplicationBaseService
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function invite()
    {
        $role = Role::where('name', 'Client')->first();
        $this->model->roles()->sync($role);
        $this->model->invite();
    }
}
