<?php


	namespace App\Filters\CRM\Traits;

    use Illuminate\Database\Eloquent\Builder;

    trait PublicAccessFilterTrait
	{
        public function publicRoleAccess($value = 'no')
        {
            $this->builder->when($value == 'no', function (Builder $query) {
                $query->where('owner_id', auth()->user()->id);
            });
        }
	}
