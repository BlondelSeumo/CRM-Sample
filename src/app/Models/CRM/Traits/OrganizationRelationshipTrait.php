<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Organization\Organization;

trait OrganizationRelationshipTrait
{
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
