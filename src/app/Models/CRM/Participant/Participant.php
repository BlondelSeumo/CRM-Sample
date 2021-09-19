<?php

namespace App\Models\CRM\Participant;

use App\Models\Core\BaseModel;
use App\Models\CRM\Person\Person;

class Participant extends BaseModel
{
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
