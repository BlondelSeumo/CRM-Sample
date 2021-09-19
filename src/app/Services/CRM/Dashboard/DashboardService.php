<?php

namespace App\Services\CRM\Dashboard;

use App\Filters\CRM\Dashboard\DashboardFilter;
use App\Models\Core\Status;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Pipeline\Pipeline;
use App\Models\CRM\Proposal\Proposal;
use App\Models\CRM\User\User;
use App\Services\ApplicationBaseService;
use Carbon\Carbon;

class DashboardService extends ApplicationBaseService
{
    public $filter;
    public function __construct(DashboardFilter $filter)
    {
        $this->statuses = $this->getStatus();
        $this->filter = $filter;
    }

    public function dealOverView($deals)
    {
        // Deals Overview

        $weekContainer = array_fill(0, 7, 0);
        $openWeekMap = $weekContainer;
        $wonWeekMap = $weekContainer;
        $lostWeekMap = $weekContainer;

        $totalWeekMap = $weekContainer;

        // Month of Year Section
        $yearContainer = array_fill(0, 12, 0);
        $openYearMap = $yearContainer;

        $wonYearMap = $yearContainer;

        $lostYearMap = $yearContainer;

        $totalYearMap = $yearContainer;
        $checkLastOrThisMonth = request()->has('last_month')
            ? now()->subMonth()->daysInMonth
            : now()->daysInMonth;
        $monthContainer = array_fill(1, $checkLastOrThisMonth, 0);
        $month = $monthContainer;
        $monthWon = $monthContainer;
        $monthLost = $monthContainer;
        $monthTotal = $monthContainer;


        // Total Deal Count
        $totalDeal = $this->dealStatusCount($deals);

        // Deal Count
        $openDeal = $this->dealStatusCount($deals, $this->statuses['status_open']);


        // Total Won Deal
        $wonDeal = $this->dealStatusCount($deals, $this->statuses['status_won']);

        // Total Lost Deal
        $lostDeal = $this->dealStatusCount($deals, $this->statuses['status_lost']);


        // Status Open Filter
        $statusOpenFilter = $this->dealStatusPluckFilter($deals, $this->statuses['status_open']);

        list($openWeekMap, $month, $openYearMap)
            = $this->statusFilters($statusOpenFilter, $openWeekMap, $month, $openYearMap);

        // Status Won Filter
        $statusWonFilter = $this->dealStatusPluckFilter($deals, $this->statuses['status_won']);

        list($wonWeekMap, $monthWon, $wonYearMap)
            = $this->statusFilters($statusWonFilter, $wonWeekMap, $monthWon, $wonYearMap);

        // Status Lost Filter
        $statusLostFilter = $this->dealStatusPluckFilter($deals, $this->statuses['status_lost']);

        list($lostWeekMap, $monthLost, $lostYearMap)
            = $this->statusFilters($statusLostFilter, $lostWeekMap, $monthLost, $lostYearMap);


        // Total Filter
        $totalFilter = $this->dealStatusPluckFilter($deals);

        list($totalWeekMap, $monthTotal, $totalYearMap)
            = $this->statusFilters($totalFilter, $totalWeekMap, $monthTotal, $totalYearMap);


        $dealsOverview = [];

        if (\request()->has('last_seven_days') || \request()->has('this_week') || \request()->has('last_week')) {
            array_push(
                $dealsOverview,
                $openWeekMap,
                $wonWeekMap,
                $lostWeekMap,
                $totalWeekMap
            );
        } elseif (\request()->has('this_month') || \request()->has('last_month')) {
            array_push(
                $dealsOverview,
                collect($month)->values(),
                collect($monthWon)->values(),
                collect($monthLost)->values(),
                collect($monthTotal)->values()
            );
        } elseif (\request()->has('this_year') || \request()->has('total')) {
            array_push(
                $dealsOverview,
                $openYearMap,
                $wonYearMap,
                $lostYearMap,
                $totalYearMap
            );
        }

        return [
            'deal_over_view' => $dealsOverview,
            'total_deal_overview' => $totalDeal,
            'open_deal' => $openDeal,
            'won_deal' => $wonDeal,
            'lost_deal' => $lostDeal,
        ];
    }

    public function dealStatusCount($deals, $status = null)
    {
        return $deals
            ->when($status, function ($query) use ($status) {
                return $query->where('status_id', $status);
            })->when(!$status, function ($query) use ($status) {
                return $query->whereNotNull('status_id');
            })->count();
    }

    public function dealStatusPluckFilter($deals, $status = null)
    {
        return $deals
            ->when($status, function ($query) use ($status) {
                return $query->where('status_id', $status);
            })->pluck('created_at');
    }

    public function statusFilters($filters, $WeekMap, $month, $YearMap)
    {

        foreach ($filters as $value) {
            if (\request()->has('last_seven_days') || \request()->has('this_week') || \request()->has('last_week')) {
                ++$WeekMap[Carbon::parse($value)->dayOfWeek];
            } elseif (\request()->has('this_month') || \request()->has('last_month')) {
                ++$month[Carbon::parse($value)->day];
            } elseif (\request()->has('this_year') || \request()->has('total')) {
                ++$YearMap[Carbon::parse($value)->month - 1];
            }
        }
        return [$WeekMap, $month, $YearMap];
    }

    public function dealsCountByStatus($status)
    {
        return Deal::filters($this->filter)->where('status_id', $status)->count();
    }

    public function totalOrganization()
    {
        return Organization::count();
    }

    public function totalPeople()
    {
        return Person::count();
    }

    public function totalParticipation()
    {
        return \DB::table('activity_participant')->distinct('person_id')->count();
    }

    public function totalEmployee()
    {
        return User::where('status_id', '=', '1')->get()->count();
    }

    public function totalOwner()
    {
        return Deal::distinct('owner_id')->pluck('owner_id');
    }

    public function totalCollaborator()
    {
        return \DB::table('activity_collaborator')->distinct('user_id');
    }

    public function bothProposal($status = null)
    {
        return Proposal::when($status, function ($query) use ($status) {
            return $query->where('status_id', $status);
        })->count();
    }

    public function pipeline($request = null)
    {
        return $request ?
            Pipeline::withCount(['deals' => function ($query) use ($request) {
                $query->where('status_id', $request);
            }])->get()
            :
            Pipeline::count();
    }

    public function topFiveOwners($status)
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Client');
        })
            ->withCount(['deals'])
            ->where('status_id', $status)
//            ->orwhereNotNull('first_name')
//            ->orwhereNotNull('last_name')
            ->limit(5)
            ->orderBy('deals_count', 'DESC')
            ->get();
    }

    public function getStatus()
    {
        return Status::where('type', 'deal')
            ->orWhere('type', 'proposal')
            ->orWhere('type', 'user')
            ->pluck('id', 'name');
    }
}
