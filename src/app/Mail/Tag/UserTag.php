<?php


namespace App\Mail\Tag;


use App\Mail\Traits\TemplateVariableReplace;
use Illuminate\Support\Facades\URL;

class UserTag extends Tag
{
    use TemplateVariableReplace;
    protected $user;

    public function __construct($user, $notifier = null, $receiver = null)
    {
        $this->user = $user;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = config('notification.user_front_end_route_name');
    }

    public function passwordReset($token)
    {
        return $this->variableReplace($token);
//        return [
//            '{receiver_name}' => $this->user->full_name,
//            '{app_logo}' => asset(config('settings.application.company_logo')),
//            '{app_name}' => config('settings.application.company_name'),
//            '{reset_password_url}' => URL::signedRoute('reset-password.index', ['token' => $token, 'email' => $this->user->email])
//        ];
    }

    public function invitation()
    {
       return $this->variableReplace();
//        return [
//            '{receiver_name}' => $this->user->full_name ?? 'There',
//            '{app_logo}' => asset(config('settings.application.company_logo')),
//            '{app_name}' => config('settings.application.company_name'),
//            '{action_by}' => $this->notifier->full_name,
//            '{invitation_url}' => URL::signedRoute('user-invite.index', ['invitation_token' => $this->user->invitation_token])
//        ];
    }

    public function notification()
    {
        return [
            '{receiver_name}' => $this->user->full_name ?? 'There',
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{name}' => $this->user->full_name,
            '{email}' => $this->user->email,
            '{action_by}' => $this->notifier->full_name ?? '',
        ];
    }
    public function passwordResetSubject()
    {
        return [
            '{name}' => optional($this->user)->full_name,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
        ];
    }
    public function invitationSubject()
    {
        return [

            '{name}' => optional($this->user)->full_name,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
        ];
    }
}
