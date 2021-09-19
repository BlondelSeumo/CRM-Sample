<?php

namespace App\Services\CRM\Proposal;


use App\Models\CRM\Proposal\Proposal;
use App\Services\ApplicationBaseService;
use Carbon\Carbon;

class ProposalService extends ApplicationBaseService
{
    public function __construct(Proposal $proposal)
    {
        $this->model= $proposal;
    }

    public function setGroupColumn($group_by, $item)
    {
        if ($group_by == 'owner_id') {
            return [
                'id' => $item->first()->owner->id,
                'name' => $item->first()->owner->name
            ];

        }
    }

    public function getAgeOfActivity($start, $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        return $start->diffindays($end);
    }
}