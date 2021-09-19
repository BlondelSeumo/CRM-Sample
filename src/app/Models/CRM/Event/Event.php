<?php

namespace App\Models\CRM\Event;

use App\Models\Core\BaseModel;
use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\Event\Traits\EventRelationsTrait;
use App\Models\CRM\Event\Traits\EventRules;

class Event extends BaseModel
{
    use EventRelationsTrait,
        EventRules;

    protected $fillable = ['name', 'status_id', 'contact_type_id'];

    public function ContactType()
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id', 'id');
    }

    public function contextable()
    {
        return $this->morphTo();
    }
}
