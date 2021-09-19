<?php

namespace App\Filters\CRM;


use App\Filters\Core\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class TagFilter extends BaseFilter
{
    public function tag($tag = null)
    {
        return $this->builder->when($tag, function (Builder $builder) use ($tag) {

            $builder->where('name', 'LIKE', "%" . $tag . "%");

        });
    }
}