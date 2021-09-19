<?php


	namespace App\Filters\CRM\Traits;


	use Illuminate\Database\Eloquent\Builder;

    trait PhoneFilterTrait
	{
        public function phones($ids = null)
        {
            $phones = explode(',', $ids);
            return $this->builder->when($ids, function (Builder $query) use ($phones) {
                return $query->whereHas('phone', function (Builder $query) use ($phones) {
                    $query->whereIn('id', $phones);
                });
            });
        }
	}
