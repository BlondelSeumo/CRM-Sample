<?php


namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DealValueFilter
{
    public function dealValue($value = null)
    {

        $value = json_decode(htmlspecialchars_decode($value), true);
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereBetween('value', array_values($value));
        });

    }
}
