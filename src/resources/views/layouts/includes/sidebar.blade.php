@php
    $data = [
        [
            'icon' => 'pie-chart',
            'name' => __('default.dashboard'),
            'url' => request()->root().'/admin/dashboard',
            'permission' => authorize_any(['manage_dashboard']),
        ],
        [
            'id' => 'leads',
            'icon' => 'users',
            'name' => __('default.leads'),
            'permission' => authorize_any(['view_persons', 'view_organizations', 'view_types']),
            'subMenu' => [
                [
                    'name' => __('default.people'),
                    'url' => request()->root().'/person/list',
                    'permission' => authorize_any(['view_persons']),
                ],
                [
                    'name' => __('default.organizations'),
                    'url' => request()->root().'/org/list',
                    'permission' => authorize_any(['view_organizations']),
                ],
                [
                    'name' => __('default.lead_groups'),
                    'url' => request()->root().'/contact/type/list',
                    'permission' => authorize_any(['view_types']),
                ],
            ],
        ],

        [
            'id' => 'deals',
            'icon' => 'clipboard',
            'name' => __('default.deals'),
            'permission' => authorize_any(['view_deals', 'view_pipelines', 'view_lost_reasons']),
            'subMenu' => [
                [
                    'name' => __('default.pipeline_view'),
                    'url' => request()->root().'/deals/pipeline/view',
                    'permission' => authorize_any(['page_deals_pipeline']),

                ],
                [
                    'name' => __('default.all_deals'),
                    'url' => request()->root().'/deals/list/view',
                    'permission' => authorize_any(['view_deals']),

                ],
                [
                    'name' => __('default.pipelines'),
                    'url' => request()->root().'/pipelines/list/view',
                    'permission' => authorize_any(['view_pipelines']),
                ],
                 [
                    'name' => __('default.lost_reasons'),
                    'url' => request()->root().'/lost/reasons/list/view',
                    'permission' => authorize_any(['view_lost_reasons']),

                ],
            ],
        ],
        [
            'id' => 'proposals',
            'icon' => 'hexagon',
            'name' => __('default.proposals'),
            'permission' => authorize_any(['view_proposals']),
            'subMenu' => [
                [
                    'name' => __('default.proposal_list'),
                    'url' => request()->root().'/proposals/list/view',
                    'permission' => authorize_any(['view_proposals']),
                ],
                [
                    'name' => __('default.templates'),
                    'url' => request()->root().'/template/view',
                    'permission' => authorize_any(['view_templates']),
                ],
            ],
        ],
        [
            'id' => 'activities',
            'icon' => 'activity',
            'name' => __('default.activities'),
            'permission' => authorize_any(['view_activities']),
            'subMenu' => [
                [
                    'name' => __('default.calendar_view'),
                    'url' => request()->root().'/activities/calendar/view/',
                    'permission' => authorize_any(['view_activities']),
                ],
                [
                    'name' => __('default.activity_list'),
                    'url' => request()->root().'/activities/list/view',
                    'permission' => authorize_any(['view_activities']),
                ],
            ],
        ],
        [
            'id' => 'reports',
            'icon' => 'bar-chart',
            'name' => __('default.reports'),
             'permission' => authorize_any(['deal_reports', 'proposal_reports', 'pipeline_reports']),
            'subMenu' => [
                [
                    'name' => __('default.deal'),
                    'url' => request()->root().'/reports/deal',
                    'permission' =>  authorize_any(['deal_reports']),
                ],
                [
                    'name' => __('default.proposal'),
                    'url' => request()->root().'/reports/proposal',
                    'permission' => authorize_any(['proposal_reports']),
                ],
                [
                    'name' => __('default.pipeline'),
                    'url' => request()->root().'/reports/pipeline',
                    'permission' => authorize_any(['pipeline_reports']),
                ],
            ],
        ],
        [

           'icon' => 'user-check',
           'name' => __('default.user_and_role'),
           'url' => request()->root().'/users/list',
           'permission' => authorize_any(['view_users']),
        ],
        [
            'icon' => 'settings',
            'name' => __('default.settings'),
            'url' => request()->root().'/settings/page',
            'permission' => authorize_any(['view_settings']),

        ],
    ];
@endphp
<sidebar :data="{{ json_encode($data) }}"
         :logo-icon-src="{{json_encode(env('APP_URL').config('settings.application.company_icon'))}}"
         :logo-src="{{json_encode(env('APP_URL').config('settings.application.company_logo'))}}"
         logo-url="{{env('APP_URL')}}">

</sidebar>
