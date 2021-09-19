<?php


namespace App\Services\CRM\Activity;


use App\Models\CRM\Activity\ActivityType;
use App\Services\ApplicationBaseService;

class ActivityTypeService extends ApplicationBaseService
{
    public function __construct(ActivityType $activityType)
    {
        $this->model = $activityType;
    }

}
