<?php


namespace App\Models\CRM\Deal\Traits;


trait LostReasonRules
{

    public function createdRules(): array
    {
        return [
            'lost_reason' => 'required|max:255|unique:lost_reasons,lost_reason',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'lost_reason' => "required|max:255|unique:lost_reasons,lost_reason," . request()->get('id'),
        ];
    }

}
