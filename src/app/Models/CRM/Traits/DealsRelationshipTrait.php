<?php


namespace App\Models\CRM\Traits;


use App\Models\CRM\Deal\Deal;

trait DealsRelationshipTrait
{
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
