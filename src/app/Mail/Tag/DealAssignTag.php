<?php


namespace App\Mail\Tag;


class DealAssignTag extends Tag
{

    protected $deal;

    public function __construct($deal, $notifier = null)
    {
        $this->deal = $deal;
        $this->notifier = $notifier;
    }

    public function notification()
    {
        return [
            '{receiver_name}' => $this->deal->owner->full_name,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{deal_name}' => $this->deal->title,
            '{action_by}' => $this->notifier->full_name ?? '',
        ];
    }

    public function dealSubject()
    {
        return [
            '{deal_name}' => $this->deal->title,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
        ];
    }
}
