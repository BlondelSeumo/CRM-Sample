<?php


namespace App\Models\CRM\Activity\Traits\Rules;


trait ActivityRules
{
    public function createdRules()
    {
        return [
            'activity_type_id' => 'required',
            'title' => 'required',
            'contextable_type' => 'required',
            'contextable_id' => 'required',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i'
        ];
    }

    public function updatedRules()
    {
        return [
            'title' => 'required',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i'
        ];
    }

}
