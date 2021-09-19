<?php return [
  'CRM\\Dashboard\\DashboardController@index' => 
  [
    0 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@index',
      'parameters' => NULL,
      'hadRequest' => 'status@status_open',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'dashboard',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602319636,
    ],
  ],
  'CRM\\Dashboard\\DashboardController@dealOverView' => 
  [
    0 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@dealOverView',
      'parameters' => NULL,
      'hadRequest' => 'last_seven_days@true',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'last seven days',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602319914,
    ],
    1 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@dealOverView',
      'parameters' => NULL,
      'hadRequest' => 'this_week@true',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'this week',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602319944,
    ],
    2 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@dealOverView',
      'parameters' => NULL,
      'hadRequest' => 'last_week@true',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'last week',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602319968,
    ],
    3 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@dealOverView',
      'parameters' => NULL,
      'hadRequest' => 'this_month@true',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'this month',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602320065,
    ],
    4 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@dealOverView',
      'parameters' => NULL,
      'hadRequest' => 'last_month@true',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'last month',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602320112,
    ],
    5 => 
    [
      'controller' => 'CRM\\Dashboard\\DashboardController@dealOverView',
      'parameters' => NULL,
      'hadRequest' => 'this_year@true',
      'dbpanel_custom_namespace' => NULL,
      'label' => 'this year',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602320150,
    ],
  ],
  'CRM\\Activity\\ActivityController@store' => 
  [
    0 => 
    [
      'controller' => 'CRM\\Activity\\ActivityController@store',
      'parameters' => NULL,
      'hadRequest' => 'activity_type_id@6|title@test activity title|contextable_type@App\\Models\\CRM\\Deal\\Deal|contextable_id@1',
      'dbpanel_custom_namespace' => 'App\\Http\\Requests\\CRM\\Activity\\ActivityRequest',
      'label' => 'store',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602579451,
    ],
    1 => 
    [
      'controller' => 'CRM\\Activity\\ActivityController@store',
      'parameters' => NULL,
      'hadRequest' => 'activity_type_id@6|title@test activity title|contextable_type@App\\Models\\CRM\\Deal\\Deal|contextable_id@1',
      'dbpanel_custom_namespace' => 'App\\Http\\Requests\\CRM\\Activity\\ActivityRequest',
      'label' => 'required',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602580726,
    ],
    2 => 
    [
      'controller' => 'CRM\\Activity\\ActivityController@store',
      'parameters' => NULL,
      'hadRequest' => 'activity_type_id@6|title@test activity title|contextable_type@App\\Models\\CRM\\Deal\\Deal|contextable_id@1|person_id@1,2,3,10|owner_id@1,2',
      'dbpanel_custom_namespace' => 'App\\Http\\Requests\\CRM\\Activity\\ActivityRequest',
      'label' => 'participants and collaboration',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1602580888,
    ],
  ],
  'CRM\\Report\\DealReportController@dataTable' => 
  [
    0 => 
    [
      'controller' => 'CRM\\Report\\DealReportController@dataTable',
      'parameters' => NULL,
      'hadRequest' => 'status@12|pipeline@1|deal_value@1|stages@by_stages|countValue@2|group_by@owner_id|orderBy@desc',
      'dbpanel_custom_namespace' => 'Illuminate\\Http\\Request',
      'label' => 'won deal report',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1611039603,
    ],
    1 => 
    [
      'controller' => 'CRM\\Report\\DealReportController@dataTable',
      'parameters' => NULL,
      'hadRequest' => 'status@13|pipeline@1|deal_value@1|stages@by_stages|countValue@2|group_by@owner_id|orderBy@desc',
      'dbpanel_custom_namespace' => 'Illuminate\\Http\\Request',
      'label' => 'lost deal report',
      'dbpanel_auth_id' => '1',
      'controller_prefix_namespace' => 'App\\Http\\Controllers\\',
      'created_at' => 1611040225,
    ],
  ],
];