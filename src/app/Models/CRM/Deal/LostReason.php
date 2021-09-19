<?php

namespace App\Models\CRM\Deal;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Deal\Traits\LostReasonRules;

class LostReason extends BaseModel
{
    use CreatedByRelationship,
        LostReasonRules,
        DescriptionGeneratorTrait,
        BootTrait;

    protected $fillable = ['lost_reason', 'created_by'];

    protected static $logAttributes = [
        'lost_reason', 'created_by'
    ];
}
