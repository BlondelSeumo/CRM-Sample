<?php

namespace App\Filters\CRM;

use App\Models\Core\Status;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\ActivityFilterTrait;

class ActivityFilter extends FilterBuilder
{
    use DateFilterTrait, ActivityFilterTrait;

    public function doneActivity($done = null)
    {
        $status = Status::findByNameAndType('status_todo', 'activity');

        if ($done != 1) {
            $this->builder->where('status_id', $status->id);
        }

        return $this->builder;
    }

    public function schedule($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween('started_at', array_values($date));
        });
    }
}
