<?php

namespace App\Models\CRM\Activity\Traits;

trait ActivityTypeRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|unique:activity_types|min:2|max:120'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|unique:activity_types|min:2|max:120'
        ];
    }
}
