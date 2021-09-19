<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonOrganization extends Model
{
    use HasFactory;

    protected $table = 'person_organization';

    public $timestamps = false;
}
