<?php


namespace App\Services\CRM\Activity;


use App\Models\CRM\Activity\Activity;
use App\Services\ApplicationBaseService;

class ActivityService extends ApplicationBaseService
{
    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    public function showAllActivities()
    {
        return $this->model->where('status_id','<',5)
            ->with([
                'contextable',
                'activityType:id,name',
                'CreatedBy:id,first_name,last_name',
                'tags:id,name,color_code,taggable_id'
            ])
            ->orderByDesc('created_at')
            ->paginate(
                request('per_page', '15')
            );
    }
}
