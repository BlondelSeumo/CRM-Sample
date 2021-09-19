<?php

namespace App\Filters\CRM;

use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\DealValueFilter;
use App\Filters\CRM\Traits\DealWithProposalsFilterTrait;
use App\Filters\CRM\Traits\OwnerFilterTrait;
use App\Filters\CRM\Traits\PublicAccessFilterTrait;
use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\CRM\Traits\TagsFilterTrait;
use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Builder;

class DealFilter extends UserActivityFilter
{
    use OwnerFilterTrait,
        DateFilterTrait,
        DealValueFilter,
        DealWithProposalsFilterTrait,
        TagsFilterTrait,
        StatusFilterTrait,
        PublicAccessFilterTrait;

    public function clientRoleAccess($value = 'no')
    {
        $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;
        $this->builder->when($value == 'client', function (Builder $query) use ($personId){
            $query->whereHas('contactPerson', function (Builder $query) use ($personId){
                $query->where('person_id', '=', $personId);
            });
        });
    }

    public function context($type)
    {
        // person
        if ($type == 'person') {
            return $this->builder->where(function (Builder $query) {
                $query->where('contextable_type', 'App\\Models\\CRM\\Person\\Person');
            });
        }

        // organization
        if ($type == 'organization') {
            return $this->builder->where(function (Builder $query) {
                $query->where('contextable_type', 'App\\Models\\CRM\\Organization\\Organization');
            });
        }
    }

    public function pipeline($id = null)
    {
        if ($id == 'all') {
            return $this->builder;
        }
        return $this->builder->where('pipeline_id', $id);
    }

    public function contactType($ids = null)
    {
        $contact_type = explode(',', $ids);

        return $this->builder->when($ids, function (Builder $builder) use ($contact_type) {
            $builder->whereHas('contactPerson', function (Builder $builder) use ($contact_type) {
                $builder->whereIn('contact_type_id', $contact_type);
            });
        });
    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('title', 'LIKE', "%$search%");
        });
    }

    public function selectedDeals($dealsId = null)
    {
        $dealsId = explode(',', $dealsId);
        return $this->builder->when($dealsId, function (Builder $builder) use ($dealsId) {
            $builder->whereIn('id', $dealsId);
        });
    }
}
