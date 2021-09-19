<?php


namespace App\Mail\Tag;


class ClientDealTag extends Tag
{
    protected $deal;

    protected $user;

    public function __construct($user, $deal, $notifier = null)
    {
        $this->deal = $deal;
        $this->user = $user;
        $this->notifier = $notifier;
    }

    public function notification(): array
    {
        return [
            '{receiver_name}' => $this->user->full_name ?? 'There',
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{deal_name}' => $this->deal->title,
            '{pipeline_name}' => $this->deal->pipeline->name,
            '{action_by}' => $this->notifier->full_name ?? '',
        ];
    }

    public function dealSubject(): array
    {
        return [
            '{deal_name}' => $this->deal->title,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
        ];
    }
}
