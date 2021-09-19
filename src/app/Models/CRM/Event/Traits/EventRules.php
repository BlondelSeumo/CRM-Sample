<?php

namespace App\Models\CRM\Event\Traits;

trait EventRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
