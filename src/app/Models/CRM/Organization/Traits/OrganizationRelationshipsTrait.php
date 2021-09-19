<?php

namespace App\Models\CRM\Organization\Traits;

use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Follower\Follower;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Traits\CustomFieldRelationshipTrait;
use App\Models\CRM\Traits\EmailRelationshipTrait;
use App\Models\CRM\Traits\FollowerRelationshipTrait;
use App\Models\CRM\Traits\NoteRelationshipTrait;
use App\Models\CRM\Traits\OwnerRelationshipTrait;
use App\Models\CRM\Traits\PhoneRelationshipTrait;
use App\Models\CRM\Traits\ProfilePictureRelationshipTrait;
use App\Models\CRM\Traits\TagRelationshipTrait;

trait OrganizationRelationshipsTrait
{
    use CreatedByRelationship,
        OwnerRelationshipTrait,
        TagRelationshipTrait,
        FollowerRelationshipTrait,
        ProfilePictureRelationshipTrait,
        PhoneRelationshipTrait,
        EmailRelationshipTrait,
        NoteRelationshipTrait,
        CustomFieldRelationshipTrait;

    public function contactType()
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id', 'id');
    }

    public function persons()
    {
        return $this->belongsToMany(
            Person::class,
            'person_organization',
            'organization_id',
            'person_id'
        )
            ->withPivot('job_title');
    }

    public function linkedContact()
    {
        return $this->persons();
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

    public function activity()
    {
        return $this->morphMany(Activity::class, 'contextable');
    }

    protected function getTotalPeoplesAttribute()
    {
        return $this->belongsToMany(Person::class, 'person_organization')
            ->count();
    }

    protected function getTotalFollowersAttribute()
    {
        return $this->morphMany(Follower::class, 'contextable')
            ->count();
    }
}
