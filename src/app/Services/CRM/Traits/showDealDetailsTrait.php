<?php

namespace App\Services\CRM\Traits;

use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Person\Person;

trait showDealDetailsTrait
{
    public function dealDetails($id)
    {
        $data = Deal::where('id', $id)
            ->withCount(['followers','participants'])
            ->with([
                'pipeline',     'stage',       'CreatedBy',
                'lostReason',   'status',       'tags',
                'owner.profilePicture',
                'customFields',
                'contactPerson' => function ($query) {
                    $query->select('id', 'name', 'address', 'contact_type_id', 'owner_id')
                        ->with([
                            'owner:id,first_name,last_name',
                            'contactType:id,name,class',      'profilePicture',
                            'email.type',               'phone.type',
                            'organizations.profilePicture'
                        ]);
                },
                'contextable' => function ($query) {
                    $query->select('id', 'name', 'address', 'contact_type_id', 'owner_id')
                        ->with([
                            'owner:id,first_name,last_name', 'owner.profilePicture',
                            'contactType:id,name,class',      'profilePicture',
                            'email.type',                    'phone.type',
                        ]);
                },
                'followers' => function ($follower) {
                    $follower->select(['id', 'person_id', 'contextable_id'])
                        ->with([
                            'person' => function ($email) {
                                $email->select(['id', 'name'])
                                    ->with(['email',    'phone',    'profilePicture']);
                            },
                        ]);
                },
                'participents' => function ($participants) {
                    $participants->select('id', 'person_id', 'deal_id')
                        ->with([
                            'person' => function ($query) {
                                $query->select('id', 'name', 'contact_type_id')
                                    ->with([
                                        'contactType:id,name,class',
                                        'profilePicture',
                                    ]);
                            },
                        ]);
                },
                'activity' => function ($query) {
                    $query->with([
                        'activityType',
                        'createdBy',
                        'status',
                        'participants.profilePicture',
                        'collaborators.profilePicture'
                    ]);
                },
                'proposals.CreatedBy',
                'discussions.responsibleUser'
            ])->when(!auth()->user()->can('manage_public_access'), function ($query) {
                $query->where('owner_id', auth()->user()->id);
            })->firstOrFail();

        //Lead type check
        $personLeadType = new \ReflectionClass(Person::class);
        $data['lead_type'] = $data['contextable_type'] == $personLeadType->getName() ? 1 : 2;

        $data['histories']=$data['histories'] == null ? [] : json_decode($data['histories']);
        return $data;
    }
}
