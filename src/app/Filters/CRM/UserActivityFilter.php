<?php


namespace App\Filters\CRM;


use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\FilterBuilder;
use App\Models\Core\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UserActivityFilter extends FilterBuilder
{
    use StatusFilterTrait;

    public function incomplete()
    {
        $todo = Status::where('name', 'status_todo')
            ->where('type', 'activity')->first();

        $this->builder->when($todo, function (Builder $query) use ($todo) {
            $query->where('ended_at', '<=', Carbon::parse()->yesterday())
            ->whereHas('status', function (Builder $query) use ($todo) {
                $query->where('id', $todo['id']);
            });
        });
    }

    public function file()
    {
        $this->builder->whereNotNull('file');
    }

    public function note()
    {
        $this->builder->whereNotNull('note');
    }
}
