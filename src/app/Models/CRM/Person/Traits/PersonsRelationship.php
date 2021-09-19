<?php

namespace App\Models\CRM\Person\Traits;

use App\Models\Core\Builder\Form\CustomFieldValue;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Follower\Follower;
use App\Models\CRM\Note\Note;
use App\Models\CRM\File\File;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Traits\CustomFieldRelationshipTrait;
use App\Models\CRM\Traits\TagRelationshipTrait;
use App\Models\CRM\Traits\NoteRelationshipTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\CRM\Traits\EmailRelationshipTrait;
use App\Models\CRM\Traits\OwnerRelationshipTrait;
use App\Models\CRM\Traits\PhoneRelationshipTrait;
use App\Models\CRM\Traits\FollowerRelationshipTrait;
use App\Models\CRM\Traits\ProfilePictureRelationshipTrait;

trait PersonsRelationship
{
    use CreatedByRelationship,
        OwnerRelationshipTrait,
        PhoneRelationshipTrait,
        EmailRelationshipTrait,
        TagRelationshipTrait,
        FollowerRelationshipTrait,
        ProfilePictureRelationshipTrait,
        NoteRelationshipTrait,
        CustomFieldRelationshipTrait;

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'person_organization')
            ->withPivot('job_title');
    }

    public function linkedContact()
    {
        return $this->organizations();
    }

    public function deals()
    {
        return $this->morphMany(Deal::class, 'contextable');
    }

    public function openDeals()
    {
        return $this->morphMany(Deal::class, 'contextable')->where('status_id', 13);
    }

    public function closeDeals()
    {
        return $this->morphMany(Deal::class, 'contextable')->whereIn('status_id', [14, 15]);
    }

    public function dealPerson()
    {
        return $this->belongsToMany(Deal::class, 'deal_people');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'contextable');
    }

    protected function getTotalOrganizationsAttribute()
    {
        return $this->belongsToMany(Organization::class, 'person_organization')
            ->count();
    }

    protected function getTotalFollowersAttribute()
    {
        return $this->morphMany(Follower::class, 'contextable')
            ->count();
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function customFields()
    {
        return $this->morphMany(CustomFieldValue::class, 'contextable');
    }
}
