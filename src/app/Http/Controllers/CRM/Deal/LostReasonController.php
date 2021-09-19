<?php

namespace App\Http\Controllers\CRM\Deal;

use App\Filters\CRM\LostReasonFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\LostReason\LostReasonRequest as Request;
use App\Models\CRM\Deal\LostReason;
use App\Services\CRM\Deal\LostReasonService;

class LostReasonController extends Controller
{
    public function __construct(LostReasonService $lostReasonService, LostReasonFilter $filter)
    {
        $this->service = $lostReasonService;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->with('CreatedBy:id,first_name,last_name')
            ->filters($this->filter)
            ->paginate(\request('per_page', 15));
    }


    public function store(Request $request)
    {
        $this->service->save();

        return created_responses('lost_reason');
    }

    public function show(LostReason $lostReason)
    {
        return $lostReason;
    }


    public function update(Request $request, LostReason $lostReason)
    {
        $lostReason->update($request->all());

        return updated_responses('lost_reason');
    }

    public function destroy(LostReason $lostReason)
    {
        if ($lostReason->delete())
        {
            return deleted_responses('lost_reason');
        }
    }
}
