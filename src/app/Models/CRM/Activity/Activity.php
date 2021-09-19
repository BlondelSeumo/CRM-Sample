<?php

namespace App\Models\CRM\Activity;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\CRM\Activity\Traits\ActivityRelations;
use App\Models\CRM\Activity\Traits\Rules\ActivityRules;
use App\Models\CRM\Traits\TagRelationshipTrait;

class Activity extends BaseModel
{
    use CreatedByRelationship,
        StatusRelationship,
        TagRelationshipTrait,
        ActivityRules,
        ActivityRelations,
        DescriptionGeneratorTrait,
        BootTrait;

    protected $fillable = [
        'title',
        'description',
        'activity_type_id',
        'contextable_type',
        'contextable_id',
        'created_by',
        'status_id',
        'started_at',
        'ended_at',
        'start_time',
        'end_time'
    ];

    protected static $logAttributes = [
        'title', 'activityType.name' => 'contextable', 'person', 'owner'
    ];

    public function contextable()
    {
        return $this->morphTo();
    }

    protected $appends = ['icon'];

}
