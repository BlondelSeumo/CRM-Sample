<?php

namespace App\Http\Resources\CRM\Deal;

use App\Models\Core\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' =>  $this->id,
            'title' =>  $this->title,
            'value' =>  $this->value,

            'pipeline_id' =>  $this->pipeline_id,
            'pipeline_name' =>  $this->pipeline->name,

            'stage_id' =>  $this->stage_id,
            'stage_name' => $this->stage->name,
            'stage_probability' =>  $this->stage->probability,
            'stage_pipeline_id' =>  $this->stage->pipeline_id,

            'lost_reason_id' =>  $this->lostReason->id,
            'lost_reason' =>  $this->lostReason->lost_reason,

            'status_id' =>  $this->status_id,
            'status_name' =>  $this->status->name,
            'status_class' =>  $this->status->class,
            'status_type' =>  $this->status->type,
            'status_translated_name' =>  $this->status->translated_name,

            'owner_id' =>  $this->owner_id,
            'owner_first_name' =>  $this->owner->first_name,
            'owner_last_name' =>  $this->owner->last_name,
            'owner_full_name' =>  $this->owner->full_name,

            'person_id' =>  $this->person_id,
            'person_name' =>  $this->person->name,
            'person_address' =>  $this->person->address,
            'person_contact_type_id' =>  $this->person->contact_type_id,

            'organization_id' =>  $this->organization_id,
            'organization_name' =>  $this->organization->name,
            'organization_address' =>  $this->organization->address,
            'organization_contact_type_id' =>  $this->organization->contact_type_id,

            'expired_at' =>  $this->expired_at,
            'created_at' =>  $this->created_at,
            'updated_at' =>  $this->updated_at,

            'created_by' => $this->CreatedBy->id,
            'created_first_name' => $this->CreatedBy->first_name,
            'created_last_name' => $this->CreatedBy->last_name,
            'created_full_name' => $this->CreatedBy->full_name,

            //'created_by' => User::whereId($this->created_by)->get(['id', 'first_name', 'last_name']),
        ];
    }
}
