<?php

namespace App\Models\CRM\Contact;

use App\Models\Core\BaseModel;

class PersonOrganization extends BaseModel
{
    protected $table = 'person_organization';

    protected $fillable = ['person_id', 'organization_id'];
}
