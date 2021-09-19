<?php

namespace App\Filters\CRM;


use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class TemplateFilter extends FilterBuilder
{
    use DateFilterTrait;
    public function ownerIs($ids = null)
    {
        $owner_id = explode(',', $ids);
        return $this->builder->when($ids, function (Builder $builder) use ($owner_id) {
            $builder->whereIn('created_by', $owner_id);
        });
    }
    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('subject', 'LIKE', "%$search%");
        });
    }
}
