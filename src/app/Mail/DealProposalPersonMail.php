<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DealProposalPersonMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $proposal;
    protected $templateContent;
    protected $personName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($proposal, $templateContent, $personName, $subject)
    {
        $this->proposal = $proposal;
        $this->template = $templateContent;
        $this->name = $personName;
        $this->subject = $subject;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('crm.mail.deal_person_proposal')
                ->with([
                    'email' => $this->proposal,
                    'template' => $this->template,
                    'name' => $this->name
                ]);
    }
}
