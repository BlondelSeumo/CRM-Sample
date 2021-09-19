<?php


namespace App\Models\CRM\Proposal\Traits;


trait ProposalRules
{
    public function createdRules()
    {
        return [
            'subject' => 'required|max:255',
            'content' => 'required',
            'status_id' => 'required'
        ];
    }
}
