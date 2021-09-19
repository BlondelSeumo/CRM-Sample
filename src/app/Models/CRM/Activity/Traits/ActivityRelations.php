<?php

namespace App\Models\CRM\Activity\Traits;

use App\Models\CRM\Activity\ActivityType;
use App\Models\CRM\Person\Person;
use App\Models\Core\Auth\User;

trait ActivityRelations
{
    public function ActivityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id', 'id');
    }

    public function participants()
    {
        return $this->belongsToMany(Person::class, 'activity_participant');
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'activity_collaborator');
    }

    public function getIconAttribute()
    {
        $type = $this->ActivityType()->first('icon');

        if ($type)
            return $type->icon;

        // if ($this->file()->first())
        //     return config('crm-config.icon.for_file_icon');

        // if ($this->note()->first())
        //     return config('crm-config.icon.for_note_icon');
    }
}
