<?php

namespace App\Models\CRM\User;

use App\Models\Core\Auth\User as CoreUser;
use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Deal\Deal;

class User extends CoreUser
{
    public function activity()
    {
        return $this->hasManyThrough(
            Activity::class,
            Deal::class,
            'person_id',
            'deal_id',
            'id',
            'id'
        );
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'owner_id');
    }
}
