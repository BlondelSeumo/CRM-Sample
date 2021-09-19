<?php

namespace App\Models\CRM\Pipeline\Traits;

use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Builder;

trait PipelineAdditionalDataTrait
{
    /*
     * Get total stages
     */
    public function getTotalStagesAttribute()
    {
        return (int) $this->stage()->count();
    }

    /*
     * Get total deals
     */
    public function getTotalDealsAttribute()
    {
        return (int) $this->deals()->count();
    }

    /*
     * Get the summation of deal values
     */
    public function getTotalDealValueAttribute()
    {
        if (!app()->runningInConsole()) {
            if (request()->has('clientRoleAccess')) {
                $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;
                return (int)$this->deals()
                    ->whereHas('contactPerson', function (Builder $query) use ($personId){
                        $query->where('person_id', '=', $personId);
                    })->sum('value');
            }
            return (int)$this->deals()
                ->when(!auth()->user()->can('manage_public_access'), function ($query) {
                    $query->where('created_by', auth()->user()->id);
                })
                ->sum('value');
        }
    }
}
