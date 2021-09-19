<?php

namespace App\Models\CRM\Traits;

use App\Models\Core\Auth\User;
use App\Models\CRM\User\User as CRMUser;

trait OwnerRelationshipTrait
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function setOwnerIdAttribute($value)
    {
        if(is_null($value) && auth()->user())
            $value = auth()->user()->id;
        $this->attributes['owner_id'] = $value;
    }

}
