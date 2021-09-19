<?php

namespace App\Http\Controllers\CRM\Report;

use App\Filters\CRM\ProposalFilter;
use App\Http\Controllers\Controller;
use App\Services\CRM\Proposal\ProposalService;
use App\Http\Requests\CRM\Proposal\ProposalRequest as Request;

class ProposalReportController extends Controller
{
    public function __construct(ProposalService $proposalService, ProposalFilter $filter)
    {
        $this->service = $proposalService;
        $this->filter = $filter;
    }

    public function chart(){

        $collection = $this->service
            ->with([
                'owner:id,first_name,last_name',
            ])
            ->filters($this->filter)
            ->get()
            ->whereNotNull('owner')
            ->groupBy('owner.full_name')
            ->map(function($proposals,$owner){
                return [
                    'owner' => $owner,
                    'counter' => count($proposals)
                ];
            });
        // average counter need for display in charts
        if(!$collection->isEmpty()) $collection->push(['owner'=> trans('custom.average'),'counter'=>$collection->avg('counter')]);

        // sorted by counter value desc
        $sorted = $collection->sortByDesc('counter');

        $formattedData = [];

        // need to return array of object
        foreach($sorted as $key => $value){
            array_push($formattedData,$value);
        }
        return $formattedData;
    }

    public function dataTable(Request $request)
    {
        $collection = $this->service
            ->with([
                'owner:id,first_name,last_name',
                'deal:id,title,pipeline_id,status_id,created_at,expired_at',
            ])
            ->filters($this->filter)
            ->get()
            ->whereNotNull('owner')
            ->groupBy('owner_id')
            ->map(function($proposals){
                return [
                    'owner' =>  $this->service->setGroupColumn('owner_id', $proposals),
                    'counter' => count($proposals),
                    'age_of_activity' => $this->service->getAgeOfActivity($proposals->min('sent_at'), $proposals->max('sent_at')),
                    'started_date' => $proposals->min('sent_at'),
                    'end_date' => $proposals->max('sent_at'),
                ];
            });

        // need to return array of object with paginator for datatable
        $formattedData = [];

        foreach($collection as $key => $value){
            array_push($formattedData,$value);
        }

        return $this->service->paginate(
            $formattedData,
            request('per_page', '10'),
            request('page', '1')
        );
    }

    public function proposalReportDetails(Request $request)
    {
        $collection = $this->service
            ->filters($this->filter)
            ->with([
                'deal.contactPerson:id,name',
                'deal',
            ])
            ->where('owner_id', $request->id)
            ->get()
            ->values();
        return $this->service->paginate($collection, request("per_page", 15), request("page", 1));
    }
}
