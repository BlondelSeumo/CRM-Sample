<?php


namespace App\Filters\CRM\Traits;


trait PipelineFilterTrait
{
    public function pipeline($ids = null)
    {
        $pipeline_id = explode(',', $ids);
        return $this->builder->whereIn('pipeline_id', $pipeline_id);
    }
}
