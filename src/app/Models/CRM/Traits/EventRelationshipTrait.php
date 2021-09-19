<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Event\Event;

trait EventRelationshipTrait
{
    public function event()
    {
        return $this->morphMany(Event::class, 'contextable');
    }
}
