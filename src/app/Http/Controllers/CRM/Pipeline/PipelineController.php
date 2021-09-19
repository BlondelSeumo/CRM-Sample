<?php

namespace App\Http\Controllers\CRM\Pipeline;

use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Stage\Stage;
use App\Filters\CRM\PipelineFilter;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CRM\Pipeline\Pipeline;
use Illuminate\Support\Facades\Session;
use App\Models\CRM\Import\PipelineImport;
use App\Services\CRM\Pipeline\PipelineService;
use App\Notifications\CRM\Pipeline\PipelineNotification;
use App\Http\Requests\CRM\PipeLine\PipelineRequest as Request;

class PipelineController extends Controller
{
    public function __construct(PipelineService $pipelineService, PipelineFilter $pipelineFilter)
    {
        $this->service = $pipelineService;
        $this->filter = $pipelineFilter;
    }

    public function index()
    {
        if (request()->has('all')) {
            return $this->service
                ->filters($this->filter)
                ->get();
        }

        if (request()->has('clientRoleAccess')) {
            return $this->service->clientPipelines()
                ->filters($this->filter)
                ->paginate(
                    request('per_page', '15')
                );
        }

        return $this->service->pipelines()
            ->filters($this->filter)
            ->paginate(
                request('per_page', '15')
            );
    }

    public function create()
    {
        return view('crm.deals.add-pipeline');
    }

    public function store(Request $request)
    {
        $pipeline = $this->service->save();

        if ($request->stage) {
            foreach ($request->stage as $item) {
                $pipeline->stage()->updateOrCreate($item);
            }
        }

        notify()
            ->on('pipeline_created')
            ->with($pipeline)
            ->send(PipelineNotification::class);

        return created_responses('pipelines', ['data' => $pipeline]);
    }

    public function edit($id)
    {
        return view('crm.deals.edit-pipeline', compact('id'));
    }

    public function show(Pipeline $pipeline)
    {
        return $pipeline->load(['stage' => function ($query) {
            $query->withCount('deals');
        }]);
    }

    public function update(Request $request, Pipeline $pipeline)
    {
        $pipeline->update($request->only('name'));

        foreach ($request->stage as $item) {
            if (collect($item)->has('id')) {
                Stage::where('id', $item['id'])->update($item);
            } else {
                Stage::create(collect($item)->merge(['pipeline_id' => $pipeline->id])->toArray());
            }
        }
        Session::put('pipelineId', $pipeline->id);

        notify()
            ->on('pipeline_updated')
            ->with($pipeline)
            ->send(PipelineNotification::class);

        return updated_responses('pipelines', ['data' => $pipeline]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pipeline $pipeline)
    {
        $deal = new Deal();
        // move deals in another pipeline -> request('move_to')
        // update deals table with those data
        // before delete the pipeline
        if (request()->has('move_to')) {
            $stage_id_of_top_priority = Pipeline::find(request('move_to'))
                ->stage()
                ->get()
                ->sortBy('priority')
                ->first()
                ->id;

            $pipeline->deals()->update([
                'pipeline_id' => request('move_to'),
                'stage_id' => $stage_id_of_top_priority,
            ]);
        }

        $pipelineDealIds = $pipeline->load('deals')->deals->pluck('id');
        Activity::whereIn('contextable_id', $pipelineDealIds)
                                        ->where('contextable_type', get_class($deal))
                                        ->delete();

        notify()
            ->on('pipeline_deleted')
            ->with($pipeline)
            ->send(PipelineNotification::class);

        if ($pipeline->delete()) {
            return deleted_responses('pipelines');
        }
    }

    public function pipelineImport(\Illuminate\Http\Request $request)
    {
        $file = $request->file('import_file');
        Excel::import(new PipelineImport, $file);

        return [
            'status' => 200,
            'message' => trans('default.pipelines') . ' ' . trans('default.has_been_imported_successfully'),
        ];
    }
}
