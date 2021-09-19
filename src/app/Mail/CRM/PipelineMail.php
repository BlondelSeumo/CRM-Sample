<?php

namespace App\Mail\CRM;

use App\Mail\Tag\PipelineTag;
use App\Models\Core\Auth\User;
use App\Models\CRM\Pipeline\Pipeline;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PipelineMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $pipeline;

    protected $auth;

    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
        $this->auth = auth()->user();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = $this->template();

        $tag = new PipelineTag($this->pipeline, $this->auth);

        return $this->view('crm.mail.pipeline.template', [
            'template' => $template->parse(
                $tag->createTemplate()
            )
        ])->subject($template->parseSubject(
            method_exists($tag, 'pipelineSubject') ? $tag->pipelineSubject() : ['{name}' => $this->pipeline->name]
        ));
    }


    public function template()
    {
        return NotificationTemplateHelper::new()
            ->on('pipeline_created')
            ->mail();
    }
}
