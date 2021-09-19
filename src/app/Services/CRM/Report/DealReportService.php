<?php

namespace App\Services\CRM\Report;

use App\Models\Core\Status;
use App\Models\CRM\Deal\Deal;
use App\Services\ApplicationBaseService;
use App\Services\CRM\Traits\showDealDetailsTrait;


class DealReportService extends ApplicationBaseService
{
    use showDealDetailsTrait;

    public function __construct(Deal $deal)
    {
        $this->model = $deal;
    }

    public function showDeal()
    {
        return $deals = $this->model::query()
            ->with([
                'pipeline',
                'stage' => function ($query) {
                    $query->select('id', 'name', 'probability', 'pipeline_id');
                },
                'CreatedBy',
                'lostReason:id,lost_reason',
                'status',
                'contextable',
                'contactPerson',
                'owner',
                'proposals'
            ]);
    }

    public function getFilterByStageOrOwner($stages, $group_by)
    {
        if ($stages != 0) {

            if ($group_by == 'owner_id') {
                $whereColumn = 'stage_id';
                $whereValue = $stages;
            } else {
                $whereColumn = 'owner_id';
                $whereValue = $stages;
            }

            return [$whereColumn => $whereValue];

        }
        return null;
    }

    public function setGroupColumn($group_by, $item)
    {
        if ($group_by == 'stage_id') {
            return [
                'id' => $item->first()->stage->id,
                'name' => $item->first()->stage->name
            ];

        } elseif ($group_by == 'lost_reason_id') {

            return [
                'id' => $item->first()->lostReason->id,
                'name' => $item->first()->lostReason->lost_reason
            ];

        } else {
            return [
                'id' => $item->first()->owner->id,
                'name' => $item->first()->owner->full_name
            ];
        }
    }


    public function getAverageDealAge($collection)
    {
        return $collection->map(function ($query) {
            $from = $query->created_at;
            $to = $query->updated_at;

            if($query->status->name == 'status_open'){
                $to= now();
            }
            return abs(strtotime($to) - strtotime($from));
        });
    }

    public function dataSetReportsChart($data)
    {

        $modified_data = $this->formatDataAndAverage($data->pluck('labels'), '#5a86f1', '#46cc97');

        return [
            'dataSet' => [
                [
                    'barPercentage' => 0.5,
                    'barThickness' => 25,
                    'borderWidth' => 1,
                    'borderColor' => $modified_data['colors'],
                    'backgroundColor' => $modified_data['colors'],
                    'data' => $data->pluck('data')
                ]
            ],
            'labels' => $data->pluck('labels')
        ];
    }

    public function formatDataAndAverage($data, $data_color, $average_color)
    {
        $colors = [];

        foreach ($data as $index => $item) {

            if ($item == trans('custom.average')) {
                array_push($colors, $average_color);
            } else {
                array_push($colors, $data_color);
            }
        }

        return ['colors' => $colors];
    }

    public function getStatus($deal_strategy)
    {
        $allStatusIds = Status::where('type', '=', 'deal')->get();
        $lostWonStatusIds = Status::where('type', '=', 'deal')
            ->where('name', '!=', 'status_open')
            ->get();

        if ($deal_strategy == 1)
        {
             return $allStatusIds->pluck('id')->implode(',');
        }
        else if ($deal_strategy == 2)
        {
            return Status::findByNameAndType('status_open', 'deal')->id;
        }
        else
        {
            return $lostWonStatusIds->pluck('id')->implode(',');
        }
    }
}
