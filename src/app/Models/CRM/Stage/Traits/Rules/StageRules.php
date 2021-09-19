<?php


namespace App\Models\CRM\Stage\Traits\Rules;


use Illuminate\Validation\Rule;

trait StageRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|max:255',
            'pipeline_id' => [
                'required',
                Rule::exists('pipelines', 'id')
            ],
        ];
    }
    public function updatedRules()
    {
        return [
            'name' => 'required|max:255',
            'pipeline_id' => [
                'required',
                Rule::exists('pipelines', 'id')
            ],
        ];
    }
}
