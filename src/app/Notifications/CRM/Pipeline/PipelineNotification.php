<?php

namespace App\Notifications\CRM\Pipeline;

use App\Mail\Tag\PipelineTag;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PipelineNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $pipeline)
    {
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $pipeline;
        $this->auth = auth()->user();
        $this->tag = PipelineTag::class;
        parent::__construct();
    }


    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('deals_pipeline.page', ['pipeline' => $this->model->id]);

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{pipeline_name}' => $this->model->name,
            '{app_name}' => config('settings.application.company_name')
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{pipeline_name}' => $this->model->name
        ]);


    }
}
