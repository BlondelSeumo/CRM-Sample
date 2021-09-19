<?php

namespace App\Filters\CRM\Traits;


use Illuminate\Database\Eloquent\Builder;

trait OwnerFilterTrait
{
    public function ownerIs($ids = null)
    {

        if (!empty($ids)) {
            $owner_id = explode(',', $ids);
            return $this->builder->when($ids, function (Builder $builder) use ($owner_id) {
                $builder->whereIn('owner_id', $owner_id);
            });
        }   
        return $this->builder;
    }
}
