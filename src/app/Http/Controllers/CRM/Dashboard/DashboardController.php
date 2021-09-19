<?php

namespace App\Http\Controllers\CRM\Dashboard;

use App\Filters\CRM\Dashboard\DashboardFilter;
use App\Http\Controllers\Controller;
use App\Models\CRM\Deal\Deal;
use App\Services\CRM\Dashboard\DashboardService;

class DashboardController extends Controller
{
    public function __construct(DashboardService $service, DashboardFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        // Deal Both
        $dealChart = [];
        $totalDealsChartElement = [];
        $totalDeal = 0;

        list($dealChart, $totalDealsChartElement, $totalDeal) =
            $this->dealChartElement($dealChart, $totalDealsChartElement, $totalDeal);

        //Contact
        $totalContact = 0;
        $contacts = [];
        list($totalContact, $contacts) = $this->contactInformation($totalContact, $contacts);

        // Both Employees
        $totalEmployee = 0;
        $employees = [];
        list($totalEmployee, $employees) = $this->employeeInformation($totalEmployee, $employees);

        // Pipeline

        $totalProposal = $this->service->bothProposal();
        $totalAcceptedProposal = $this->service->bothProposal($this->service->statuses['status_accepted']);
        $totalSendProposal =
            $this->service->bothProposal($this->service->statuses['status_sent']) + $totalAcceptedProposal;

        // Sending Rate

        $sendingRate = $totalProposal > 0 ? intval(($totalSendProposal / $totalProposal) * 100) : 0;
        $acceptanceRate = $totalSendProposal > 0 ? intval(($totalAcceptedProposal / $totalSendProposal) * 100) : 0;

        // Pipeline

        $pipelineName = [];
        $pipelineTotalDeals = [];
        $pipelineBackgroundColor = [];

        $totalPipeline = $this->service->pipeline();

        list($pipelineName, $pipelineTotalDeals, $pipelineBackgroundColor) =
            $this->dealPipeline($pipelineName, $pipelineTotalDeals, $pipelineBackgroundColor);


        // Top Five owners

        $topFiveOwners = $this->service->topFiveOwners($this->service->statuses['status_active']);

        $fiveOwnerName = [];
        $fiveOwnerDeals = [];
        foreach ($topFiveOwners as $value) {
            array_push($fiveOwnerName, $value->full_name);
            array_push($fiveOwnerDeals, $value->deals_count);
        }

        if (auth()->user()->can('manage_public_access')) {

            return [
                'deals_chart' => $dealChart,
                'total_deals_chart_element' => $totalDealsChartElement,
                'total_deal' => $totalDeal,
                'total_contact' => $totalContact,
                'contacts' => $contacts,
                'total_employee' => $totalEmployee,
                'employees' => $employees,
                'total_send_proposal' => $totalSendProposal,
                'total_accepted_proposal' => $totalAcceptedProposal,
                'sending_rate' => $sendingRate,
                'acceptance_rate' => $acceptanceRate,
                'total_pipeline' => $totalPipeline,
                'deals_on_pipeline_name' => $pipelineName,
                'pipeline_total_deals' => $pipelineTotalDeals,
                'background_color' => $pipelineBackgroundColor,
                'top_five_owners_name' => $fiveOwnerName,
                'five_owner_deal' => $fiveOwnerDeals,
            ];
        } else {
            return [
                'deals_chart' => $dealChart,
                'total_deals_chart_element' => $totalDealsChartElement,
                'total_deal' => $totalDeal,
            ];
        }
    }

    public function dealChartElement(array $dealChart, array $totalDealsChartElement, $totalDeal)
    {
        $openDeal = $this->service->dealsCountByStatus($this->service->statuses['status_open']);
        $wonDeal = $this->service->dealsCountByStatus($this->service->statuses['status_won']);
        $lostDeal = $this->service->dealsCountByStatus($this->service->statuses['status_lost']);

        // Total Deals Chart Element
        array_push(
            $dealChart,
            $openDeal,
            $wonDeal,
            $lostDeal
        );

        array_push(
            $totalDealsChartElement,
            ['value' => $openDeal, 'key' => 'open'],
            ['value' => $wonDeal, 'key' => 'won'],
            ['value' => $lostDeal, 'key' => 'lost']
        );

        $totalDeal = ($openDeal + $wonDeal + $lostDeal);

        return [$dealChart, $totalDealsChartElement, $totalDeal];

    }

    public function contactInformation($totalContact, array $contacts)
    {
        $totalOrganization = $this->service->totalOrganization();
        $totalPeople = $this->service->totalPeople();
        $totalParticipation = $this->service->totalParticipation();
        $totalContact = ($totalOrganization + $totalPeople);

        array_push(
            $contacts,
            ['value' => $totalOrganization, 'key' => 'organization'],
            ['value' => $totalPeople, 'key' => 'people'],
            ['value' => $totalParticipation, 'key' => 'participation']
        );

        return [$totalContact, $contacts];
    }

    public function employeeInformation($totalEmployee, array $employees)
    {
        $totalEmployee = $this->service->totalEmployee();

        $totalOwner = $this->service->totalOwner();

        $totalCollaborator = $this->service->totalCollaborator()->pluck('user_id');

        $totalBothOwnerCollaborator = $totalOwner->values()->intersect($totalCollaborator->values());

        array_push(
            $employees,
            ['value' => $totalOwner->count(), 'key' => 'works_as_owner'],
            ['value' => $totalCollaborator->count(), 'key' => 'works_as_collaborators'],
            ['value' => $totalBothOwnerCollaborator->count(), 'key' => 'works_as_boot_owner_collaborators']
        );

        return [$totalEmployee, $employees];
    }

    public function dealPipeline($pipelineName, $pipelineTotalDeals, $pipelineBackgroundColor)
    {
        // Deals on pipeline
        $dealsOnPipeline = $this->service->pipeline($this->service->statuses[\request()->status]);
        if (count($dealsOnPipeline)) {
            $dealsOnPipeline->push(['name' => trans('custom.average'),
                'deals_count' => $dealsOnPipeline->avg('deals_count'),]);

            $sorted = $dealsOnPipeline->sortByDesc('deals_count');

            foreach ($sorted as $key => $value) {
                if ($value['name'] == 'Average') {
                    array_push($pipelineBackgroundColor, '#00C754');
                } else {
                    array_push($pipelineBackgroundColor, '#4466F2');
                }

                array_push($pipelineName, $value['name']);
                array_push($pipelineTotalDeals, $value['deals_count']);
            }
        }
        return [$pipelineName, $pipelineTotalDeals, $pipelineBackgroundColor];
    }

    public function dealOverView(): array
    {

        $deals = Deal::filters($this->filter)->get();

        return $this->service->dealOverView($deals);
    }

}
