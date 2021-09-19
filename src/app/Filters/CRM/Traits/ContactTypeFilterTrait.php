<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ContactTypeFilterTrait
{
    public function contactType($ids = null)
    {
        $contact_type = explode(',', $ids);
        return $this->builder->when($ids, function (Builder $builder) use ($contact_type) {
            $this->builder->whereIn('contact_type_id', $contact_type);
        });
    }
}
