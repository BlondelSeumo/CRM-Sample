<?php

namespace App\Notifications\CRM\Deal;

use App\Mail\Tag\DealTag;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;


class DealNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;


    public function __construct($templates, $via, $deal)
    {
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $deal;
        $this->auth = auth()->user();
        $this->tag = DealTag::class;
        parent::__construct();
    }

    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('deal_details.page', $this->model->id);

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{deal_name}' => $this->model->title,
            '{app_name}' => config('settings.application.company_name')
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{deal_name}' => $this->model->title
        ]);
    }
}
