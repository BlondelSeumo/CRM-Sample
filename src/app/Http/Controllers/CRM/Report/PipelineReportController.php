<?php

namespace App\Http\Controllers\CRM\Report;

use App\Models\Core\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CRM\Report\PipelineReportService;
use App\Filters\CRM\ReportFilter\PipelineReportFilter;

class PipelineReportController extends Controller
{
    public function __construct(PipelineReportService $report, PipelineReportFilter $filter)
    {
        $this->service = $report;
        $this->filter = $filter;

        $this->statuses = $this->getStatus();
    }

    public function dataTable(Request $request)
    {
        $this->deal_strategy = \request('deal_strategy', '1');
        $this->whereStatus = $this->service->getFilterByStatus($this->deal_strategy);
        $this->group_by = \request('groupBy', 'owner_id');
        $this->whereOwner = $this->service->getFilterByOwner($request->owner, $this->group_by);
        $collection = $this->service
            ->showPipeline()
            ->filters($this->filter)
            ->first();

        if (!$collection){
            return $this->service->paginate($collection, request('per_page', 15), request('page', 1));
        }
        $tempCollection = $collection->deals->whereNotNull('owner');

        if ($this->whereStatus) {
            $clause = $this->whereStatus['clause'];
            $name = $this->whereStatus['name'];
            $tempCollection = $tempCollection->$clause('status_id', [$this->statuses[$name]]);
        }

        if ($this->group_by == 'stage_id' && $this->whereOwner) {
            $tempCollection = $tempCollection->where('owner_id', $request->owner);
        }

        $tempCollection = $tempCollection->groupBy($this->group_by)
            ->map(function ($deals) {
                $stage = $deals->countBy('stage.name');
                $status = $deals->countBy('status.name');

                //To make key processable for data table
//                $modifiedStages = array_merge(...$stage->keys()->map(function ($k) use ($stage) {
//                    return [
//                        Str::snake($k) => $stage[$k],
//                    ];
//                })->toArray());

                if ($this->group_by == 'owner_id') {
                    return [
                        'group_by' => $this->service->setGroupColumn($this->group_by, $deals),
                        'pipeline_stages' => $stage,
                        'total_no_of_deals' => $deals->count(),
                        'total_deal_won' => $deals->where('status_id', $this->statuses['status_won'])->count(),
                        'total_deal_lost' => $deals->where('status_id', $this->statuses['status_lost'])->count(),
                    ];
                }

                return [
                    'group_by' => $this->service->setGroupColumn($this->group_by, $deals),
                    'deal_started' => $deals->count(),
                    'total_deal_value' => $deals->sum('value'),
                    'avg_deal_value' => $deals->avg('value'),
                    'avg_deal_age' => $this->service->getAverageDealAge($deals)->avg(),
                ];
            });

        // need to return array of object with paginate for datatable
        $formattedData = [];

        foreach ($tempCollection->values() as $key => $value) {
            array_push($formattedData, $value);
        }

        return $this->service->paginate($formattedData, request('per_page', 15), request('page', 1));
    }

    public function chart(Request $request)
    {
        $this->group_by = \request('groupBy', 'owner_id');
        $this->deal_strategy = \request('deal_strategy', '1');
        $this->relation = str_replace('_id', '', $this->group_by);
        $this->whereOwner = $this->service->getFilterByOwner($request->owner, $this->group_by);
        $this->whereStatus = $this->service->getFilterByStatus($this->deal_strategy);

        $collection = $this->service
            ->showPipeline()
            ->filters($this->filter)
            ->first();

        if (!$collection){
            return $collection = [];
        }

        $group_by = $this->relation == 'stage' ? $this->relation.'.name' : $this->relation.'.full_name';

        $tempCollection = $collection->deals->whereNotNull('owner');

        if ($this->whereStatus) {
            $clause = $this->whereStatus['clause'];
            $name = $this->whereStatus['name'];
            $tempCollection = $tempCollection->$clause('status_id', [$this->statuses[$name]]);
        }

        if ($this->group_by == 'stage_id' && $this->whereOwner) {
            $tempCollection = $tempCollection->where('owner_id', $request->owner);
        }

        $tempCollection = $tempCollection->groupBy($group_by)->map(function ($deals) {
            if ($deals->first()) {
                //return $deals;
                $condition = $deals->first()->{$this->relation};

                return [
                    'labels' => $this->relation == 'owner' ? $condition->full_name : $condition->name,
                    'data' => \request('countValue') == 1 ? $deals->count() : $deals->sum('value'),
                ];
            }

            return [];
        });

        if (! $tempCollection->isEmpty()) {
            $tempCollection->push(
                [
                    'labels' => trans('custom.average'),
                    'data' => $tempCollection->avg('data'),
                ]
            );
        }

        $formattedData = [];

        // need to return array of object
        foreach ($tempCollection as $key => $value) {
            array_push($formattedData, $value);
        }

        $modifiedData = collect($formattedData)->sortByDesc('data')->values();

        return $this->service->dataSetReportsChart($modifiedData);
    }


    public function getStatus()
    {
        return Status::where('type', 'deal')->pluck('id', 'name');
    }
}
