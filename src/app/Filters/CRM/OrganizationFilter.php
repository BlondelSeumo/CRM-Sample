<?php


namespace App\Filters\CRM;


use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\CRM\Traits\ContactTypeFilterTrait;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\OwnerFilterTrait;
use App\Filters\CRM\Traits\PublicAccessFilterTrait;
use App\Filters\CRM\Traits\TagsFilterTrait;
use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Builder;

class OrganizationFilter extends UserActivityFilter
{
    use CreatedByFilter,
        ContactTypeFilterTrait,
        OwnerFilterTrait,
        TagsFilterTrait,
        DateFilterTrait,
        PublicAccessFilterTrait;

    public function clientRoleAccess($value = 'no')
    {
        $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;
        $this->builder->when($value == 'client', function (Builder $query) use ($personId) {
            $query->whereHas('persons', function (Builder $query) use ($personId) {
                $query->where('person_id', $personId);
            });
        });
    }

    public function person($ids = null)
    {
        $persons = explode(',', $ids);

        $this->builder->when($ids, function (Builder $query) use ($persons) {
            $query->whereHas('persons', function (Builder $query) use ($persons) {
                $query->whereIn('person_id', $persons);
            });
        });


    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where(function (Builder $builder) use ($search) {
                $builder->where('name', 'LIKE', "%$search%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        });
    }
}
