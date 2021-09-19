<?php

	namespace App\Http\Controllers\CRM\Frontend;

	use App\Http\Controllers\Controller;
	use App\Services\CRM\Settings\CrmCustomFiledService;
	use Illuminate\Support\Facades\Session;

	class FrontendController extends Controller
	{
		public function organizationList()
		{
			return view('crm.contacts.organization');
		}

		public function personView()
		{
			return view('crm.contacts.person');
		}

		public function personDetails()
		{
			return view('crm.contacts.person-details');
		}

		public function personImport()
		{
			$customKeys = resolve(CrmCustomFiledService::class)->customFieldsContext('person')->toArray();
			return view('crm.contacts.import', [
				'validKeys' => collect(array_merge(
					['name', 'email', 'phone', 'lead_group', 'created_by_email', 'owner_email', 'address', 'country', 'area', 'city', 'state', 'zip_code'],
					$customKeys))
			]);
		}

		public function organizationImport()
		{
			$customKeys = resolve(CrmCustomFiledService::class)->customFieldsContext('organization')->toArray();
			return view('crm.contacts.org-import', [
				'validKeys' => collect(array_merge(
					['name', 'lead_group', 'created_by_email', 'owner_email', 'address', 'country', 'area', 'city', 'state', 'zip_code'],

					$customKeys))
			]);
		}

		public function contactTypeList()
		{
			return view('crm.contacts.contact-type');
		}

		public function dealsListView()
		{
			return view('crm.deals.deals-list-view');
		}

		public function lostReasonListView()
		{
			return view('crm.deals.lost-reason-list-view');
		}

		public function proposalsListView()
		{
			return view('crm.proposals.proposals-list-view');
		}

		public function proposalCopy($id)
		{
			return view(
				'crm.proposals.send-proposal',
				[
					'id' => $id,
					'action' => 'copy',
				]
			);
		}

		public function templateCopy($id)
		{
			return view(
				'crm.proposals.add-template',
				[
					'id' => $id,
					'action' => 'copy',
				]
			);
		}

		public function templateView()
		{
			return view('crm.proposals.template-view');
		}

		public function userList()
		{
			return view('crm.users_roles.index');
		}

		public function activityListView()
		{
			return view('crm.activities.activities-list-view');
		}

		public function pipelineView()
		{
			return view('crm.deals.pipeline');
		}

		public function importPipelineView()
		{
			return view('crm.deals.import-pipeline');
		}

		public function dealsPipelineView()
		{
			return view('crm.deals.pipeline-view');
		}

		public function dealDetails($id)
		{
			return view('crm.deals.deal-details', compact('id'));
		}

		public function dealImport()
		{
			$customKeys = resolve(CrmCustomFiledService::class)->customFieldsContext('deal')->toArray();
			return view('crm.deals.import-deal', [
				'validKeys' => collect(array_merge([
					'title', 'value', 'pipeline_name', 'status', 'owner_email', 'lead', 'expired_at', 'created_at', 'updated_at'
				], $customKeys))
			]);
		}

		public function addEditPipeline()
		{
			return view('crm.deals.edit-pipeline');
		}

		public function settingPages()
		{
			return view('crm.settings.setting');
		}

		public function activityCalendarView()
		{
			return view('crm.activities.activities-calendar-view');
		}

		public function reportsDeal()
		{
			return view('crm.reports.deal');
		}

		public function reportsProposal()
		{
			return view('crm.reports.proposal');
		}

		public function reportsPipeline()
		{
			return view('crm.reports.pipeline');
		}

		public function notificationList()
		{
			return view('crm.notification.notification-list');
		}
	}
