<?php


namespace App\Mail\Tag;


class PipelineTag extends Tag
{
    protected $pipeline;

    public function __construct($pipeline, $notifier = null, $receiver = null)
    {
        $this->pipeline = $pipeline;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl =  route('deals_pipeline.page', ['pipeline' => $this->pipeline->id]);
    }

    public function notification()
    {
        return [
            '{receiver_name}' => $this->receiver->full_name,
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{pipeline_name}' => $this->pipeline->name,
            '{action_by}' => $this->notifier->full_name ?? '',
            '{resource_url}' => $this->resourceurl,
        ];
    }

}
