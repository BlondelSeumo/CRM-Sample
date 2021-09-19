<?php


namespace App\Models\CRM\Organization\Traits;


use App\Models\CRM\Deal\Deal;
use App\Models\Core\Status;

trait DealsAdditionalDataTrait
{
    public function getClosedDealsAttribute()
    {
//        $status = Status::query()
//            ->where('name', 'like', '%open')
//              // ->where('name', 'status_active')
//            ->first()->id;
//        return $this->hasMany(Deal::class)
//            ->where('status_id', $status)
//            ->count();

       //return Deal::where('status_id', 1)->count();
       //return Deal::whereIn('status_id', [1,2,3])->count();

//        return $this->status()->whereHas('status', function (Builder $builder) {
//            $builder->whereIn('name', [
//                'status_active', 'status_pending', 'status_invited'
//            ]);
//        })->count();
    }
}
