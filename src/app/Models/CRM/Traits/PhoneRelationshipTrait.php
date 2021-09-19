<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Phone\Phone;

trait PhoneRelationshipTrait
{
    public function phone()
    {
        return $this->morphMany(Phone::class, 'contextable');
    }
}
