<?php

namespace App\Mail;

use App\Models\CRM\Proposal\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $proposal;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($proposal)
    {
        $this->proposal = $proposal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('crm.mail.send_proposal')
            ->with([
                'proposal' => $this->proposal->content,
                'deal' => $this->proposal->deal->title,
                'full_name' => $this->proposal->deal->owner->full_name,
                'email' => $this->proposal->deal->owner->email,
            ]);
    }
}
