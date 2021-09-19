<?php


namespace App\Filters\CRM\Traits;


use Illuminate\Database\Eloquent\Builder;

trait StatusFilterTrait
{
    public function status($status = null)
    {
        if (!empty($status)) {
            $status = explode(',', $status);

            $this->builder->when($status, function (Builder $query) use ($status) {
                $query->whereHas('status', function (Builder $query) use ($status) {
                    $query->whereIn('id', $status);
                });
            });
        }
        return $this->builder;
    }
}
