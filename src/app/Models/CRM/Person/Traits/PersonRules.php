<?php

namespace App\Models\CRM\Person\Traits;

trait PersonRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|max:255',
            'contact_type_id' => 'required|integer',
            'owner_id' => 'required|integer',
        ];
    }
}
