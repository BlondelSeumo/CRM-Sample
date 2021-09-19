<?php


namespace App\Models\CRM\Organization\Traits\Rules;


trait OrganizationRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'contact_type_id' => 'required|int',
            'owner_id' => 'required|int',
        ];
    }
}
