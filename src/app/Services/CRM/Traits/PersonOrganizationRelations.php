<?php

    namespace App\Services\CRM\Traits;

    trait PersonOrganizationRelations
    {
        public function relations($pivot_data)
        {
            return [
                'contactType:id,name,class',
                'owner.profilePicture',
                'CreatedBy:id,first_name,last_name',
                'phone.type',
                'email.type',
                'country:id,name',

                $pivot_data => function ($query) {
                    $query->select(['id', 'name', 'address', 'contact_type_id', 'owner_id'])
                        ->with(['contactType:id,name,class', 'profilePicture']);
                },
                'tags:id,name,color_code',
                'profilePicture',
                'deals' => function ($deal) {
                    $deal->with([
                        'contextable.profilePicture',
                        'owner:id,first_name,last_name',
                        'owner.profilePicture'
                    ])->when(!auth()->user()->can('manage_public_access'), function ($query) {
                        $query->where('owner_id', auth()->user()->id);
                    });
                },
                'followers' => function ($follower) {
                    $follower->select(['id', 'person_id', 'contextable_id'])
                        ->with([
                            'person' => function ($email) {
                                $email->select(['id', 'name'])->with(['email', 'profilePicture']);
                            }
                        ]);
                },
                'activity' => function ($query) {
                    $query->with([
                        'activityType', 'CreatedBy', 'status',
                        'participants.profilePicture', 'collaborators.profilePicture'
                    ])->when(!auth()->user()->can('manage_public_access'), function ($query) {
                        $query->where('created_by', auth()->user()->id);
                    });
                },
            ];
        }
    }
