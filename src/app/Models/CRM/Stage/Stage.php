<?php

namespace App\Models\CRM\Stage;

use App\Models\CRM\Deal\Deal;
use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\CRM\Pipeline\Pipeline;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\CRM\Stage\Traits\Rules\StageRules;
use App\Models\Core\Traits\DescriptionGeneratorTrait;

class Stage extends BaseModel
{
    use CreatedByRelationship,
        StageRules,
        DescriptionGeneratorTrait,
        BootTrait;

    protected $fillable = [
        'name', 'probability', 'pipeline_id', 'priority', 'created_by',
    ];

    protected $casts = [
        'pipeline_id' => 'int',
    ];

    protected static $logAttributes = [
        'name', 'probability', 'pipeline', 'priority', 'createdBy',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    public function dealStage()
    {
        return $this->belongsToMany(self::class, 'deal_stage')
            ->withTimestamps();
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
