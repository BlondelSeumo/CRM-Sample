<?php

namespace App\Http\Controllers\CRM\Report;

use App\Filters\CRM\ReportFilter\DealReportFilter;
use App\Http\Controllers\Controller;
use App\Services\CRM\Report\DealReportService;
use Illuminate\Http\Request;


class DealReportController extends Controller
{
    public function __construct(DealReportService $report, DealReportFilter $filter)
    {
        $this->service = $report;
        $this->filter = $filter;
    }

    public function dataTable(Request $request)
    {
        $this->group_by = \request('group_by', 'owner_id');
        $collection = $this->service
            ->showDeal()
            ->filters($this->filter)
            ->get()
            ->whereNotNull('owner')
            ->groupBy($this->group_by)
            ->map(function ($item) {

                return [
                    'group_by' => $this->service->setGroupColumn($this->group_by, $item),
                    'average_deals_value' => $item->avg('value'),
                    'deals_won' => $item->count(),
                    'total_deals_value' => $item->sum('value'),
                    'average_age_of_deal' => $this->service->getAverageDealAge($item)->avg(),
                ];
            });

        // need to return array of object with paginate for datatable
        $formattedData = [];

        foreach ($collection as $key => $value) {
            array_push($formattedData, $value);
        }

        return $this->service->paginate($formattedData, request("per_page", 15), request("page", 1));
    }

    public function chart(Request $request)
    {

        $this->group_by = \request('group_by', 'owner_id');
        $this->relation = str_replace('_id', '', $this->group_by);
        $this->relation = $this->relation == 'lost_reason' ? 'lostReason' : $this->relation;

        $this->whereStageOrOwner = $this->service->getFilterByStageOrOwner($request->stages, $this->group_by);

        $collection = $this->service
            ->showDeal()
            ->filters($this->filter);

        if ($this->whereStageOrOwner) $collection->where($this->whereStageOrOwner);

        $collection = $collection->get()
            ->whereNotNull('owner')
            ->groupBy($this->group_by)
            ->map(function ($item) {

                $condition = $item->first()->{$this->relation};
                return [
                    'labels' => ($this->relation == 'owner' ? $condition->full_name : $this->relation == 'stage') ? $condition->name : $condition->lost_reason,
                    'data' => \request('countValue') == 1 ? $item->count() : $item->sum('value'),
                ];
            });


        if (!$collection->isEmpty()) {
            $collection->push(
                [
                    'labels' =>  trans('custom.average'),
                    'data' => $collection->avg('data')
                ]
            );
        }

        $formattedData = [];

        // need to return array of object
        foreach ($collection as $key => $value) {
            array_push($formattedData, $value);
        }

        $modifiedData = collect($formattedData)->sortByDesc('data')->values();

        return $this->service->dataSetReportsChart($modifiedData);
    }

    public function dealReportDetails(Request $request)
    {
        $collection = $this->service
            ->filters($this->filter)
            ->with([
                'contextable',
                'contactPerson',
                'stage' => function ($query) {
                    $query->select('id', 'name', 'probability', 'pipeline_id');
                },
                'lostReason:id,lost_reason',
                'owner',
                'status'
            ])
            ->where($request->groupBy, $request->id)
            ->get()
            ->values();

        return $this->service->paginate($collection, request("per_page", 15), request("page", 1));
    }

    public function pipelineReportDetails(Request $request)
    {
        $request['status'] = $this->service->getStatus($request->deal_strategy);
        $collection = $this->service
            ->filters($this->filter)
            ->with([
                'contextable',
                'status',
                'contactPerson',
                'stage' => function ($query) {
                    $query->select('id', 'name', 'probability', 'pipeline_id');
                },
                'lostReason:id,lost_reason',
                'owner'
            ])
            ->where($request->groupBy, $request->findById)
            ->get()
            ->values();

        return $this->service->paginate($collection, request("per_page", 15), request("page", 1));
    }
}
