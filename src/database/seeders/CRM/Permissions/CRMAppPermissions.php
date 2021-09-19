<?php

use App\Models\Core\Auth\Type;

$crmId = Type::findByAlias('crm')->id;

return [
    // Person Related Permissions
    [
        'name' => 'view_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'create_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'update_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'delete_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'invite_lead_person',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'import_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'export_person',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'upload_profile_picture_of_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'sync_tags_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'sync_followers_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'sync_contact_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'sync_organizations_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'view_activities_persons',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'sync_activities_person',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],
    [
        'name' => 'sync_file_person',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ], [
        'name' => 'sync_note_person',
        'type_id' => $crmId,
        'group_name' => 'persons'
    ],

    // Organization Related Permissions
    [
        'name' => 'view_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'create_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'update_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'delete_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'import_organization',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'export_organization',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],

    [
        'name' => 'upload_profile_picture_of_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_tags_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_followers_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_contact_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_person_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'person_org_deal',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_follower_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'view_activities_organizations',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_activities_organization',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_file_organization',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],
    [
        'name' => 'sync_note_organization',
        'type_id' => $crmId,
        'group_name' => 'organizations'
    ],


    // Contact Types Permissions
    [
        'name' => 'view_types',
        'type_id' => $crmId,
        'group_name' => 'lead_groups'
    ],
//    [
//        'name' => 'view_lead_groups',
//        'type_id' => $crmId,
//        'group_name' => 'lead_groups'
//    ],
    [
        'name' => 'create_types',
        'type_id' => $crmId,
        'group_name' => 'lead_groups'
    ],
    [
        'name' => 'update_types',
        'type_id' => $crmId,
        'group_name' => 'lead_groups'
    ],
    [
        'name' => 'delete_types',
        'type_id' => $crmId,
        'group_name' => 'lead_groups'
    ],


    // Deal Related Permissions
    [
        'name' => 'view_deals',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'page_deals_pipeline',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'create_deals',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'update_deals',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'delete_deals',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'import_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'export_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'move_multiple_deals',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'proposal_send_deal_person',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'sync_activities_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'sync_followers_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'sync_tags_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'sync_note_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'sync_file_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'view_activities_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'delete_person_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'delete_organization_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'sync_participants_deal',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => "revision_history_deal",
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
    [
        'name' => 'deal_reports',
        'type_id' => $crmId,
        'group_name' => 'deals'
    ],
//    [
//        'name' => 'view_deal_report',
//        'type_id' => $crmId,
//        'group_name' => 'deals'
//    ],
//    [
//        'name' => 'view_deal_report_chart',
//        'type_id' => $crmId,
//        'group_name' => 'deals'
//    ],
//    [
//        'name' => 'view_deal_report_details',
//        'type_id' => $crmId,
//        'group_name' => 'deals'
//    ],

    //Discussions
    [
        'name' => 'view_discussions',
        'type_id' => $crmId,
        'group_name' => 'discussions'
    ],
    [
        'name' => 'create_discussions',
        'type_id' => $crmId,
        'group_name' => 'discussions'
    ],
    [
        'name' => 'update_discussions',
        'type_id' => $crmId,
        'group_name' => 'discussions'
    ],
    [
        'name' => 'delete_discussions',
        'type_id' => $crmId,
        'group_name' => 'discussions'
    ],

    // Pipeline Related Permissions
    [
        'name' => 'view_pipelines',
        'type_id' => $crmId,
        'group_name' => 'pipelines'
    ],
    [
        'name' => 'create_pipelines',
        'type_id' => $crmId,
        'group_name' => 'pipelines'
    ],
    [
        'name' => 'update_pipelines',
        'type_id' => $crmId,
        'group_name' => 'pipelines'
    ],
    [
        'name' => 'delete_pipelines',
        'type_id' => $crmId,
        'group_name' => 'pipelines'
    ],
    [
        'name' => 'pipeline_reports',
        'type_id' => $crmId,
        'group_name' => 'pipelines'
    ],

    // Stages Related Permissions
    [
        'name' => 'view_stages',
        'type_id' => $crmId,
        'group_name' => 'stages'
    ],
    [
        'name' => 'create_stages',
        'type_id' => $crmId,
        'group_name' => 'stages'
    ],
    [
        'name' => 'update_stages',
        'type_id' => $crmId,
        'group_name' => 'stages'
    ],
    [
        'name' => 'delete_stages',
        'type_id' => $crmId,
        'group_name' => 'stages'
    ],

    // Deal Lost Reasons Permissions
    [
        'name' => 'view_lost_reasons',
        'type_id' => $crmId,
        'group_name' => 'lost_reasons'
    ],
    [
        'name' => 'create_lost_reasons',
        'type_id' => $crmId,
        'group_name' => 'lost_reasons'
    ],
    [
        'name' => 'update_lost_reasons',
        'type_id' => $crmId,
        'group_name' => 'lost_reasons'
    ],
    [
        'name' => 'delete_lost_reasons',
        'type_id' => $crmId,
        'group_name' => 'lost_reasons'
    ],

    // Proposals Related Permissions
    [
        'name' => 'view_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'create_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'update_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'delete_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'copy_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'send_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'add_proposals',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
    [
        'name' => 'proposal_reports',
        'type_id' => $crmId,
        'group_name' => 'proposals'
    ],
//    [
//        'name' => 'view_chart_proposals',
//        'type_id' => $crmId,
//        'group_name' => 'proposals'
//    ],
//    [
//        'name' => 'view_data_table_proposals',
//        'type_id' => $crmId,
//        'group_name' => 'proposals'
//    ],


    // Activity Related Permissions
    [
        'name' => 'view_activities',
        'type_id' => $crmId,
        'group_name' => 'activities'
    ],
    [
        'name' => 'create_activities',
        'type_id' => $crmId,
        'group_name' => 'activities'
    ],
    [
        'name' => 'update_activities',
        'type_id' => $crmId,
        'group_name' => 'activities'
    ],
    [
        'name' => 'delete_activities',
        'type_id' => $crmId,
        'group_name' => 'activities'
    ],
    [
        'name' => 'done_activities',
        'type_id' => $crmId,
        'group_name' => 'activities'
    ],


//    // Activity Types Related Permissions
//    [
//        'name' => 'view_activity_types',
//        'type_id' => $crmId,
//        'group_name' => 'activities'
//    ],
//    [
//        'name' => 'create_activity_types',
//        'type_id' => $crmId,
//        'group_name' => 'activities'
//    ],
//    [
//        'name' => 'update_activity_types',
//        'type_id' => $crmId,
//        'group_name' => 'activities'
//    ],
//    [
//        'name' => 'delete_activity_types',
//        'type_id' => $crmId,
//        'group_name' => 'activities'
//    ],


    // Templates Related Permissions
    [
        'name' => 'view_templates',
        'type_id' => $crmId,
        'group_name' => 'templates'
    ],
    [
        'name' => 'create_templates',
        'type_id' => $crmId,
        'group_name' => 'templates'
    ],
    [
        'name' => 'update_templates',
        'type_id' => $crmId,
        'group_name' => 'templates'
    ],
    [
        'name' => 'delete_templates',
        'type_id' => $crmId,
        'group_name' => 'templates'
    ],
    [
        'name' => 'copy_templates',
        'type_id' => $crmId,
        'group_name' => 'templates'
    ],


];
