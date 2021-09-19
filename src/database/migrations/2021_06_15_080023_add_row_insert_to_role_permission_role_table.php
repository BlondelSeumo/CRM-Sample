<?php

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowInsertToRolePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $superAdmin = User::first();
            $roles = Role::get()->toArray();
            $roleCheck = array_search(['Manager', 'Agent', 'Client'], $roles);
            if ($roleCheck == false) {
                $roles = [
                    [
                        'name' => 'Manager',
                        'type_id' => Type::findByAlias('app')->id,
                        'created_by' => $superAdmin->id,
                        'is_default' => 1
                    ],
                    [
                        'name' => 'Agent',
                        'type_id' => Type::findByAlias('app')->id,
                        'created_by' => $superAdmin->id,
                        'is_default' => 1
                    ],
                    [
                        'name' => 'Client',
                        'type_id' => Type::findByAlias('app')->id,
                        'created_by' => $superAdmin->id,
                        'is_default' => 1
                    ]
                ];

                Role::query()->insert($roles);
                $managerRole = Role::where('name', 'Manager')->first();
                $agentRole = Role::where('name', 'Agent')->first();
                $clientRole = Role::where('name', 'Client')->first();
                $managerPermission = Permission::whereIn('group_name',
                    [
                        'public_access_behavior',
                        'dashboard',
                        'persons',
                        'organizations',
                        'lead_groups',
                        'lost_reasons',
                        'templates',
                        'pipelines',
                        'deals',
                        'log',
                        'proposals',
                        'activities',
                        'stages',
                        'custom_fields',
                        'discussions',
                        'notifications'
                    ])->get();

                $agentPermission = Permission::whereIn('name', [
                    'view_persons', 'create_persons', 'update_persons', 'delete_persons', 'invite_lead_person',
                    'import_persons', 'upload_profile_picture_of_persons', 'sync_tags_persons', 'sync_followers_persons',
                    'sync_contact_persons', 'sync_organizations_persons', 'view_activities_persons', 'sync_activities_person',
                    'sync_file_person', 'sync_note_person', 'view_organizations', 'create_organizations', 'update_organizations',
                    'delete_organizations', 'import_organization', 'upload_profile_picture_of_organizations', 'sync_tags_organizations',
                    'sync_followers_organizations', 'sync_contact_organizations', 'sync_person_organizations', 'person_org_deal',
                    'sync_follower_organizations', 'view_activities_organizations', 'sync_activities_organization', 'sync_file_organization',
                    'sync_note_organization', 'view_lead_groups', 'view_deals', 'page_deals_pipeline', 'create_deals', 'update_deals', 'delete_deals', 'import_deal',
                    'move_multiple_deals', 'proposal_send_deal_person', 'proposal_send_deal_person', 'sync_followers_deal', 'sync_tags_deal', 'sync_note_deal',
                    'sync_file_deal', 'view_activities_deal', 'sync_activities_deal', 'delete_person_deal', 'delete_organization_deal', 'sync_participants_deal',
                    'revision_history_deal', 'view_discussions', 'create_discussions', 'update_discussions', 'delete_discussions',
                    'view_pipelines', 'view_stages', 'view_types', 'view_lost_reasons', 'view_proposals', 'create_proposals', 'update_proposals',
                    'delete_proposals', 'copy_proposals', 'send_proposals',
                    'view_activities', 'create_activities', 'update_activities', 'delete_activities', 'done_activities', 'view_templates',
                    'view_custom_fields', 'list_notifications', 'view_notification_event',
                    'update_notification_templates', 'view_notification_channels', 'manage_notification_audience'
                ])->get();

                $clientPermission = Permission::whereIn('name',
                    [
                        'client_access',
                        'view_organizations',
                        'view_deals',
                        'view_proposals',
                        'view_pipelines',
                        'user_info_lead',
                        'view_discussions',
                        'create_discussions',
                        'update_discussions',
                        'delete_discussions'
                    ])->get();

                $manager = [];
                foreach ($managerPermission as $permission) {
                    $manager[] = [
                        'permission_id' => $permission->id
                    ];
                }
                $managerRole->permissions()->sync($manager);

                $agent = [];
                foreach ($agentPermission as $permission) {
                    $agent[] = [
                        'permission_id' => $permission->id
                    ];
                }
                $agentRole->permissions()->sync($agent);

                $client = [];
                foreach ($clientPermission as $permission) {
                    $client[] = [
                        'permission_id' => $permission->id
                    ];
                }
                $clientRole->permissions()->sync($client);


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
