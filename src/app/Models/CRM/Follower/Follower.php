<?php

namespace App\Models\CRM\Follower;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Person\Person;

class Follower extends BaseModel
{
    use DescriptionGeneratorTrait;

    protected $fillable = ['person_id'];

    protected static $logAttributes = ['person'];

    public function contextable()
    {
        return $this->morphTo();
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
