<?php


namespace App\Models\CRM\Contact\Traits\Rules;


trait ContactTypeRules
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
