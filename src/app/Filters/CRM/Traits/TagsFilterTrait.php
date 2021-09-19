<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TagsFilterTrait
{
    public function tags($tags = null)
    {
        $ids = explode(',', $tags);

        $this->builder->when($tags, function (Builder $query) use ($ids) {
            $query->whereHas('tags', function (Builder $query) use ($ids) {
                $query->whereIn('id', $ids);
            });
        });
    }
}
