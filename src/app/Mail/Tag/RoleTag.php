<?php


namespace App\Mail\Tag;


use Illuminate\Support\Facades\URL;

class RoleTag extends Tag
{
    protected $role;

    public function __construct($role, $notifier, $receiver)
    {
        $this->role = $role;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = config('notification.role_front_end_route_name');
    }

    public function notification()
    {
        return [
            '{receiver_name}' => $this->receiver->full_name,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{name}' => $this->role->name,
            '{action_by}' => $this->notifier->full_name ?? 'There',
            '{resource_url}' => URL::signedRoute($this->resourceurl),
        ];
    }
}
