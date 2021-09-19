<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Person\Person;

trait PersonRelationshipTrait
{
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
