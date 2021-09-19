<?php

namespace App\Models\CRM\Activity;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Activity\Traits\ActivityTypeRules;

class ActivityType extends BaseModel
{
    use ActivityTypeRules, DescriptionGeneratorTrait;

    protected $fillable = ['name'];

    protected static $logAttributes = [
        'name'
    ];

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
