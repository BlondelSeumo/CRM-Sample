<?php

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowInsertToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $getPermission = Permission::select('id', 'name')->pluck('name')->toArray();
            $checkPermission = array_search('manage_public_access', $getPermission);
            if ($checkPermission == false) {
                $crmId = Type::findByAlias('crm')->id;
                $permissions = [

                    // System Default Role
                    [
                        'name' => 'manage_public_access',
                        'type_id' => $crmId,
                        'group_name' => 'public_access_behavior',
                    ],
                    [
                        'name' => 'client_access',
                        'type_id' => $crmId,
                        'group_name' => 'clients',
                    ],
                    [
                        'name' => 'user_info_lead',
                        'type_id' => $crmId,
                        'group_name' => 'clients'
                    ],

                    // Person Permission
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
                        'name' => 'sync_activities_person',
                        'type_id' => $crmId,
                        'group_name' => 'persons'
                    ],
                    [
                        'name' => 'sync_file_person',
                        'type_id' => $crmId,
                        'group_name' => 'persons'
                    ],
                    [
                        'name' => 'sync_note_person',
                        'type_id' => $crmId,
                        'group_name' => 'persons'
                    ],

                    // Organization Permission

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

                    // Lead Type

                    [
                        'name' => 'view_types',
                        'type_id' => $crmId,
                        'group_name' => 'lead_groups'
                    ],
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

                    //Deals Permission
                    [
                        'name' => 'page_deals_pipeline',
                        'type_id' => $crmId,
                        'group_name' => 'deals'
                    ],
                    [
                        'name' => 'proposal_send_deal_person',
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
                        'name' => "revision_history_deal",
                        'type_id' => $crmId,
                        'group_name' => 'deals'
                    ],
                    [
                        'name' => 'deal_reports',
                        'type_id' => $crmId,
                        'group_name' => 'deals'
                    ],
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

                    // Pipeline
                    [
                        'name' => 'pipeline_reports',
                        'type_id' => $crmId,
                        'group_name' => 'pipelines'
                    ],

                    // Proposal
                    [
                        'name' => 'copy_proposals',
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

                    // Template
                    [
                        'name' => 'copy_templates',
                        'type_id' => $crmId,
                        'group_name' => 'templates'
                    ],
                    //Notifications Start
                    [
                        'name' => 'list_notifications',
                        'type_id' => $crmId,
                        'group_name' => 'notifications',
                    ],
                ];

                Permission::query()->insert($permissions);
            }


        } catch (Exception $exception) {
            return $exception->getMessage();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
