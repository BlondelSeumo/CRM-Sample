<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Activity\Activity;
trait ActivityRelationshipTrait
{
    public function activity()
    {
        return $this->morphMany(Activity::class, 'contextable');
    }

    public function doneActivity()
    {
        return $this->activity()->where('status_id', '!=', 11);
    }

    public function toDoActivity()
    {
        return $this->activity()->where('status_id', 11);
    }

    public function nextActivities()
    {
        return $this->toDoActivity()
        ->with(['activityType'])
        ->where('started_at', '>=', now()->toDateString())
        ->orderBy('started_at')
        ;
    }

    public function nextActivity() {
        return $this
            ->hasOne(Activity::class,'contextable_id')
            ->with(['activityType'])
            ->where('started_at', '>=', now()->toDateString())
            ->latest('started_at');
    }
}
