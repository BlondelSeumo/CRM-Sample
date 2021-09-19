<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Email\Email;

trait EmailRelationshipTrait
{
    public function email()
    {
        return $this->morphMany(Email::class, 'contextable');
    }
}
