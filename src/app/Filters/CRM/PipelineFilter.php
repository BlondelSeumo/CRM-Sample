<?php

namespace App\Filters\CRM;


use App\Filters\CRM\Traits\ContactTypeFilterTrait;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\FilterBuilder;
use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Builder;

class PipelineFilter extends FilterBuilder
{
    use ContactTypeFilterTrait,
        DateFilterTrait;

    public function clientRoleAccess($value = 'no')
    {
        $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;
        $this->builder->when($value == 'client', function (Builder $query) use ($personId) {
            $query->whereHas('deals', function (Builder $query) use ($personId){
                $query->whereHas('contactPerson', function (Builder $query) use ($personId){
                    $query->where('person_id', '=', $personId);
                });
            });
        });
    }
    public function ownerIs($ids = null)
    {
        $owner_id = explode(',', $ids);
        return $this->builder->when($ids, function (Builder $builder) use ($owner_id) {
            $builder->whereIn('created_by', $owner_id);
        });
    }
    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', "%$search%");
        });
    }

}
