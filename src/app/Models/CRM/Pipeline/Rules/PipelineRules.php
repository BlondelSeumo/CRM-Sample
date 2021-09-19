<?php


namespace App\Models\CRM\Pipeline\Rules;


trait PipelineRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|max:255|unique:pipelines,name',
            'stage.*.name' => 'required'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|max:255|unique:pipelines,name,'.request()->get('id'),
            'stage.*.name' => 'required'
        ];
    }

}
