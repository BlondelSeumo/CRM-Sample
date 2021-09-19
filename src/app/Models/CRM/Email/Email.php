<?php

namespace App\Models\CRM\Email;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Contact\PhoneEmailType;

class Email extends BaseModel
{
    use DescriptionGeneratorTrait;

    protected $fillable = ['value', 'type_id'];

    protected static $logAttributes = [
        'value', 'type.name', 'contextable'
    ];

    use CreatedByRelationship;

    public function contextable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(PhoneEmailType::class);
    }
}
