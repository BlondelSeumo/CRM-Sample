<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Person\Person;

trait ParticipantRelationshipTrait
{
    public function participants()
    {
        return $this->belongsToMany(Person::class, 'participants');
    }
}
