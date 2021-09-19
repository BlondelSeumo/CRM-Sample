<?php

namespace App\Http\Controllers\CRM\Stage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Stage\StageRequest as Request;
use App\Models\CRM\Stage\Stage;
use App\Services\CRM\Stage\StageService;


class StageController extends Controller
{
    public function __construct(StageService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        if (\Request::exists('all'))
            return $this->service
                ->with('pipeline:id,name', 'createdBy:id,first_name,last_name')
                ->get();

        return $this->service
            ->with('pipeline:id,name', 'createdBy:id,first_name,last_name')
            ->paginate(request('per_page', 15));
    }

    public function store(Request $request)
    {
        $this->service->save();
        return created_responses('stages');
    }

    public function show(Stage $stage)
    {
        return $stage->load('pipeline:id,name');
    }

    public function update(Request $request, Stage $stage)
    {
        $stage->update($request->all());
        return updated_responses('stages');
    }

    public function destroy(Stage $stage)
    {
        // move deals in another stage -> request('move_to')
        // update deals table with those data
        // before delete the stage

        if (request()->has('move_to')) {
            $stage->deals()->update([
                'stage_id' => request('move_to'),
            ]);
        }
        if (request()->has('delete_anyway')){
           $stage->deals()->delete();
        }
        if ($stage->delete()) {
            return deleted_responses('stages');
        }
    }
}
