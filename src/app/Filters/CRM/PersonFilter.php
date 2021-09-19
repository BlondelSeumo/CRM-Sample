<?php


namespace App\Filters\CRM;


use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\CRM\Traits\ContactTypeFilterTrait;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\OwnerFilterTrait;
use App\Filters\CRM\Traits\PhoneFilterTrait;
use App\Filters\CRM\Traits\PublicAccessFilterTrait;
use App\Filters\CRM\Traits\TagsFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class PersonFilter extends UserActivityFilter
{
    use PublicAccessFilterTrait,
        CreatedByFilter,
        ContactTypeFilterTrait,
        OwnerFilterTrait,
        TagsFilterTrait,
        DateFilterTrait,
        PhoneFilterTrait;

    public function organization($ids = null)
    {
        $organizations = explode(',', $ids);

        $this->builder->when($ids, function (Builder $query) use ($organizations) {
            $query->whereHas('organizations', function (Builder $query) use ($organizations) {
                $query->whereIn('organization_id', $organizations);
            });
        });
    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where(function (Builder $builder) use ($search){
                $builder->where('name', 'LIKE', "%$search%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        });
    }
}
