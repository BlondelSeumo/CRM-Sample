<?php


namespace App\Models\CRM\Tag\Traits\Rules;


trait TagRules
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
            'name' => 'required|max:255|unique:tags,name,'.request()->get('id'),
        ];
    }
}
