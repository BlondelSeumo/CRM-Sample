<?php

    namespace App\Http\Controllers\CRM\Contact;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\CRM\Person\FollowerPersonRequest;
    use App\Models\CRM\Organization\Organization;
    use App\Services\CRM\Contact\OrganizationService;

    class OrganizationFollowerController extends Controller
    {
        public function __construct(OrganizationService $service)
        {
            $this->service = $service;
        }

        public function organizationFollower(FollowerPersonRequest $request, Organization $organization)
        {
            if ($request->has('person_id')) {
                $data = $this->service->prepareFollowersDataBeforeSync($request['person_id']);
                $this->service->followerSyncAll($organization->followers(), $data);
            }

            return updated_responses('synchronization');
        }

        public function organizationFollowers(Organization $organization)
        {
            $followers = $organization->load(['followers.person' => function ($person) {
                $person->withCount(['openDeals', 'closeDeals', ])
                    ->with(['contactType:id,name,class', 'owner:id,first_name,last_name', 'tags:id,name,color_code']);
            }])->followers->pluck('person')->flatten()->values();

            return $this->service->paginate($followers, request('per_page', 15), request('page', 1));
        }
    }
