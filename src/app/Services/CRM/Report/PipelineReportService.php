<?php

namespace App\Services\CRM\Report;


use App\Models\CRM\Pipeline\Pipeline;
use App\Services\ApplicationBaseService;

class PipelineReportService extends ApplicationBaseService
{
    public function __construct(Pipeline $pipeline)
    {
        $this->model = $pipeline;
    }

    public function showPipeline()
    {
        return $pipeline = $this->model::query()
            ->with([
                'deals.status', 'deals.stage', 'deals.owner'
            ]);
    }

    public function setGroupColumn($group_by, $item)
    {
        return $group_by == 'stage_id' ? ['id' => $item->first()->stage->id, 'name' => $item->first()->stage->name] : ['id' => $item->first()->owner->id, 'name' => $item->first()->owner->full_name];
    }

    public function getFilterByOwner($owner, $group_by)
    {

        if ($owner != 0) {

            if ($group_by == 'stage_id') {
                return ['owner_id' => $owner];
            }
        }
        return null;
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

    public function getFilterByStatus($strategy)
    {
        if ($strategy == 1) {
            //processing => all
            return null;

        } elseif ($strategy == 2) {
            //open
            return ['clause' => 'whereIn', 'name' => 'status_open'];

        } else {
            //closed = !open
            return ['clause' => 'whereNotIn', 'name' => 'status_open'];

        }

    }

}