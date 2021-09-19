<?php

namespace App\Models\CRM\Country;

use App\Models\Core\BaseModel;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    use HasFactory;

    public function person()
    {
        return $this->hasMany(Person::class);
    }
    public function organization()
    {
        return $this->hasMany(Organization::class);
    }

}
