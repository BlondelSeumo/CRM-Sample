<?php

namespace App\Mail\CRM\Deal;

use App\Mail\Tag\ClientDealTag;
use App\Mail\Tag\DealAssignTag;
use App\Models\Core\Auth\User;
use App\Models\Core\Notification\NotificationTemplate;
use App\Models\CRM\Deal\Deal;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientDealMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    protected $deal;

    protected $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Deal $deal, $event)
    {
        $this->user = $user;
        $this->deal = $deal;
        $this->event = $event;
    }

    public function build(): ClientDealMail
    {
        $template = $this->template();

        $tag = new ClientDealTag($this->user, $this->deal, auth()->user());

        return $this->view('crm.mail.template', [
            'template' => $template->parse(
                $tag->notification()
            )
        ])->subject($template->parseSubject(
            method_exists($tag, 'dealSubject') ? $tag->dealSubject() :
                ['{name}' => $this->deal->title]
        ));
    }


    public function template(): NotificationTemplate
    {
        return NotificationTemplateHelper::new()
            ->on($this->event)
            ->mail();
    }
}
