<?php


namespace App\Mail\Traits;


use Illuminate\Support\Facades\URL;

trait TemplateVariableReplace
{
    public function variableReplace($token = null)
    {
        return [
            '{receiver_name}' => $this->user->full_name ?? 'There',
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{action_by}' => $this->notifier->full_name ?? '',
            '{invitation_url}' => URL::signedRoute('user-invite.index', ['invitation_token' => $this->user->invitation_token]),
            '{reset_password_url}' => URL::signedRoute('reset-password.index', ['token' => $token, 'email' => $this->user->email])

        ];
    }
}