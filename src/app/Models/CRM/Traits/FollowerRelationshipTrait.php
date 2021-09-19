<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Follower\Follower;

trait FollowerRelationshipTrait
{
    public function followers()
    {
        return $this->morphMany(Follower::class, 'contextable');
    }
}
