<?php


namespace App\Mail\Tag;

class DealTag extends Tag
{
    public function __construct($deal, $notifier = null, $receiver = null)
    {
        $this->deal = $deal;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = route('deal_details.page', $this->deal->id);
    }
    public function notification()
    {

        return [
            '{receiver_name}' => $this->receiver->full_name,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{deal_name}' => $this->deal->title,
            '{pipeline_name}' =>$this->deal->pipeline->name,
            '{action_by}' => $this->notifier->full_name ?? 'Theare',
            '{resource_url}' => $this->resourceurl,
        ];
    }
}
