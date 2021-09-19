<?php


namespace App\Filters\CRM;


use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class ContactTypeFilter extends FilterBuilder
{
    use DateFilterTrait;

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', "%$search%");
            $builder->orWhere('class', 'LIKE', "%$search%");
        });
    }
}
