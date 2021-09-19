<?php

namespace App\Models\CRM\Note;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\Core\Traits\StatusRelationship;

class Note extends BaseModel
{
    use CreatedByRelationship, StatusRelationship, DescriptionGeneratorTrait;

    protected $fillable = ['note'];

    protected $appends = ['icon'];
    protected static $logAttributes = ['note', 'noteable'];

    public function noteable()
    {
        return $this->morphTo();
    }

    public function getIconAttribute()
    {
        return config('crm-config.icon.for_note_icon');
    }
}
