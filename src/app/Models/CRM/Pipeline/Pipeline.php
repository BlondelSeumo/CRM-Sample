<?php

namespace App\Models\CRM\Pipeline;

use App\Models\CRM\Deal\Deal;
use App\Models\Core\BaseModel;
use App\Models\CRM\Stage\Stage;
use App\Models\Core\Traits\BootTrait;
use App\Models\CRM\Pipeline\Rules\PipelineRules;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Pipeline\Traits\PipelineAdditionalDataTrait;

class Pipeline extends BaseModel
{
    use CreatedByRelationship,
        PipelineAdditionalDataTrait,
        PipelineRules,
        DescriptionGeneratorTrait,
        BootTrait;

    public function stage()
    {
        return $this->hasMany(Stage::class, 'pipeline_id')->orderBy('priority');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    protected $fillable = ['name', 'created_by'];

    protected $appends = [
        // 'total_stages',
        // 'total_deals',
        'total_deal_value'
    ];

    protected static $logAttributes = ['name', 'createdBy'];
}
