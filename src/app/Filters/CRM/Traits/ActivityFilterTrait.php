<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ActivityFilterTrait
{
    public function ownerIs($ids = null)
    {
        if($ids){
            $ids = explode(',', $ids);
            return $this->builder->whereIn('created_by',$ids);
        }
    }

    public function context($type)
    {
    
        // deal 2

        if ($type == 2) {
            return $this->builder->where(function (Builder $query) {
                $query->where('contextable_type', 'App\\Models\\CRM\\Deal\\Deal');
            });
        }

        // person 3

        if ($type == 3) {
            return $this->builder->where(function (Builder $query) {
                $query->where('contextable_type', 'App\\Models\\CRM\\Person\\Person');
            });
        }

        // organization 4

        if ($type == 4) {
            return $this->builder->where(function (Builder $query) {
                $query->where('contextable_type', 'App\\Models\\CRM\\Organization\\Organization');
            });
        }

        // any 1

        return $this->builder;
    }

    public function activity($activityType = null)
    {

        if ($activityType) {
            $activityTypes = explode(',', $activityType);
            $this->builder->whereIn('activity_type_id', $activityTypes);
        }

        return $this->builder;
    }

    public function search($search = null)
    {
        return $this->builder->where('title', 'Like', '%'.$search.'%');
    }
}
