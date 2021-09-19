<?php


namespace App\Filters\CRM;


use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBuilder
{

    public function name($name = null)
    {
        $names = explode(',', $name);
        return $this->builder->when($name, function (Builder $builder) use($names) {
            $builder->whereIn('name', $names);
        });
    }

    public function type($type = null)
    {
        $types = explode(',', $type);
        return $this->builder->when($type, function (Builder $builder) use($types) {
            $builder->whereIn('type', $types);
        });
    }

}
