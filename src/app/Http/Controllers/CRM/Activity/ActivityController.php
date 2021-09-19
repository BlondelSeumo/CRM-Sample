<?php

namespace App\Http\Controllers\CRM\Activity;

use App\Filters\CRM\ActivityFilter;
use App\Http\Controllers\Controller;
use App\Models\CRM\Activity\Activity;
use App\Services\CRM\Activity\ActivityService;
use App\Http\Requests\CRM\Activity\ActivityRequest as Request;

class ActivityController extends Controller
{
    public function __construct(ActivityService $activityService, ActivityFilter $filter)
    {
        $this->service = $activityService;
        $this->filter = $filter;
    }

    public function index(Request $request)
    {

        if (request()->has('per_page')) {
            return $this->service
            ->filters($this->filter)
            ->with([
                'CreatedBy',
                'contextable',
                'activityType:id,name',
                'contextable.CreatedBy:id,first_name,last_name',
            ])
            ->when(!auth()->user()->can('manage_public_access'), function ($query) {
                    $query->where('created_by', auth()->user()->id);
                })
            ->orderByDesc('created_at')
            ->paginate(
                request('per_page', '15')
            );
        }
        return $this->service
            ->filters($this->filter)
            ->with([
                'CreatedBy',
                'contextable',
                'activityType:id,name',
                'contextable.CreatedBy:id,first_name,last_name',
            ])
            ->when(!auth()->user()->can('manage_public_access'), function ($query) {
                $query->where('created_by', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function store(Request $request)
    {
        $activity = $this->service->save();

        if ($request->person_id) {
            $activity->participants()->sync($request->person_id);
        }

        if ($request->owner_id) {
            $activity->collaborators()->sync($request->owner_id);
        }

        return created_responses('activity');
    }

    public function show($id)
    {
        return $this->service
            ->with([
                'activityType:id,name',
                'CreatedBy:id,first_name,last_name',
                'status:id,name,class,type',
                'participants',
                'collaborators',
            ])
            ->find($id);
    }

    public function update(Request $request, Activity $activity)
    {
        $activity->update($request->all());

        if ($request->has('person_id')) {
            $activity->participants()->sync($request->person_id);
        }

        if ($request->has('owner_id')) {
            $activity->collaborators()->sync($request->owner_id);
        }

        return updated_responses('activity');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->delete()) {
            return deleted_responses('activity');
        }
    }
}
