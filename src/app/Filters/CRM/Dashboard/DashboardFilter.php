<?php


namespace App\Filters\CRM\Dashboard;


use App\Filters\CRM\Traits\PublicAccessFilterTrait;
use App\Filters\FilterBuilder;
use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardFilter extends FilterBuilder
{
    use PublicAccessFilterTrait;

    public function lastSevenDays()
    {
        return $this->builder->where('created_at', '>', Carbon::now()->subDays(7)->format('Y-m-d'));
    }

    public function thisWeek()
    {
        return $this->builder->whereBetween('created_at',
            [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->format('Y-m-d')
            ]);
    }

    public function lastWeek()
    {
        return $this->builder->whereBetween('created_at',
            [
                Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d')
            ]);
    }

    public function thisMonth()
    {
        return $this->builder->whereBetween('created_at',
            [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
    }

    public function lastMonth()
    {
        return $this->builder->whereBetween('created_at',
            [
                Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->subMonth()->lastOfMonth()->format('Y-m-d')
            ]);
    }

    public function thisYear()
    {
        return $this->builder->whereBetween('created_at',
            [
                Carbon::now()->startOfYear()->format('Y-m-d'),
                Carbon::now()->endOfYear()->format('Y-m-d')
            ]);
    }

    public function total()
    {
     return $this->builder->whereNotNull('status_id');
    }

    public function clientRoleAccess($value = 'no')
    {
        $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;
        $this->builder->when($value == 'client', function (Builder $query) use ($personId){
            $query->whereHas('contactPerson', function (Builder $query) use ($personId){
                $query->where('person_id', '=', $personId);
            });
        });
    }


}
