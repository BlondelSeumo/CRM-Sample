<?php

namespace App\Mail\CRM\Deal;

use App\Mail\Tag\DealAssignTag;
use App\Models\CRM\Deal\Deal;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DealAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $deal;

    protected $auth;

    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
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

        $tag = new DealAssignTag($this->deal, $this->auth);

        return $this->view('crm.mail.template', [
            'template' => $template->parse(
                $tag->notification()
            )
        ])->subject($template->parseSubject(
            method_exists($tag, 'dealSubject') ? $tag->dealSubject() :
                ['{name}' => $this->deal->title]
        ));
    }


    public function template()
    {
        return NotificationTemplateHelper::new()
            ->on('deal_assigned')
            ->mail();
    }
}
