<?php

namespace App\Models\CRM\Deal\Traits;

use App\Models\Core\Auth\User;
use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Discussion\Discussion;
use App\Models\CRM\File\File;
use App\Models\Core\Status;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\CRM\Activity\ActivityType;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Proposal\Proposal;
use App\Models\CRM\Stage\Stage;
use App\Models\CRM\Deal\LostReason;
use App\Models\CRM\Note\Note;
use App\Models\CRM\Pipeline\Pipeline;
use App\Models\CRM\Participant\Participant;
use App\Models\CRM\Traits\CustomFieldRelationshipTrait;
use App\Models\CRM\Traits\NoteRelationshipTrait;
use App\Models\CRM\Traits\TagRelationshipTrait;
use App\Models\CRM\Traits\OwnerRelationshipTrait;
use App\Models\CRM\Traits\ActivityRelationshipTrait;
use App\Models\CRM\Traits\FollowerRelationshipTrait;
use App\Models\CRM\Traits\ParticipantRelationshipTrait;
use Carbon\Carbon;

trait DealRelationshipTrait
{
    use OwnerRelationshipTrait,
        ParticipantRelationshipTrait,
        FollowerRelationshipTrait,
        ActivityRelationshipTrait,
        TagRelationshipTrait,
        CreatedByRelationship,
        StatusRelationship,
        NoteRelationshipTrait,
        CustomFieldRelationshipTrait;

    public function discussions()
    {
        return $this->morphMany(Discussion::class, 'commentable');
    }
    public function contextable()
    {
        return $this->morphTo();
    }
    public function contactPerson()
    {
        return $this->belongsToMany(Person::class, 'deal_people');
    }
    public function client(){
        return $this->contactPerson()->first() ?? null;
    }
    public function clientUser(){
        return User::find($this->client()->attach_login_user_id) ?? null;
    }
    public function clientUserID(){
        return $this->client()->attach_login_user_id ?? null;
    }
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function lostReason()
    {
        return $this->belongsTo(LostReason::class, 'lost_reason_id');
    }

    public function participents()
    {
        return $this->hasMany(Participant::class, 'deal_id');
    }

    public function dealStage()
    {
        return $this->belongsToMany(Stage::class, 'deal_stage')
            ->withTimestamps();
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function hasNoProposals()
    {
        return $this->proposals()->hasNoDeal();
    }

    public function getTotalFollowersAttribute()
    {
        return $this->followers()->count();
    }

    public function getTotalParticipantsAttribute()
    {
        return $this->participents()->count();
    }

    public function getNextActivityAttribute()
    {
        /**********************************************************
         * You can simply return it, instead of having it in a variable
         * if you want to handle from front-end whatever you want to show at client side.
         **********************************************************/

        $status = Status::where('name', 'status_todo')->first()->id;

        $activity = $this->activity
            ->where('started_at', '>=', Carbon::now()->toDateString())
            ->where('status_id', $status)
            ->sortBy('started_at')
            ->first();

        if (!is_null($activity)) {
            $activity_name = ActivityType::where('id', $activity->activity_type_id)->first()->name;
            return Carbon::parse($activity->started_at)->isoFormat('D MMM') . " (" . $activity_name . ")";
        } else {
            return null;
        }
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getAvgAgeOfDealAttribute()
    {
        $to = $this->updated_at;

        if($this->status->name == 'status_open'){
            $to= now();
        }

        return $this->created_at->diffindays($to);
    }

}
