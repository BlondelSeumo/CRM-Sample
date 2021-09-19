<?php

namespace App\Http\Controllers\CRM\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Activity\ActivityTypeRequest;
use App\Models\CRM\Activity\ActivityType;
use App\Services\CRM\Activity\ActivityTypeService;

class ActivityTypeController extends Controller
{
    public function __construct(ActivityTypeService $activityTypeService)
    {
        $this->service = $activityTypeService;
    }


    public function index()
    {
        return $this->service
            ->select(['id', 'name'])
            ->paginate(15);
    }
    public function store(ActivityTypeRequest $request)
    {
         $this->service->save();

        return created_responses('activity_type');
    }


    public function show(ActivityType $activityType)
    {
        return $activityType;
    }


    public function update(ActivityTypeRequest $request, ActivityType $activityType)
    {
         $activityType->update($request->all());

        return updated_responses('activity_type');
    }


    public function destroy(ActivityType $activityType)
    {
        $activityType->delete();

        return deleted_responses('activity_type');
    }
}
