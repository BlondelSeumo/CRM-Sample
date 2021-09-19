<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Role::query()->truncate();
        // Create Roles
        $superAdmin = User::first();

        $roles = [
            [
                'name' => config('access.users.app_admin_role'),
                'is_admin' => 1,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 1
            ],
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


        foreach ($roles as $role) {
            Role::query()->insert($role);
        }

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
            ])
            ->get();

        $agentPermission = Permission::whereNotIn('name', [
            'export_person', 'export_organization', 'export_deal',
            'create_types', 'update_types', 'delete_types',
            'create_templates', 'update_templates', 'delete_templates', 'copy_templates',
            'create_lost_reasons', 'update_lost_reasons', 'delete_lost_reasons',
            'create_pipelines', 'update_pipelines', 'delete_pipelines',
            'deal_reports', 'pipeline_reports', 'proposal_reports', 'add_proposals'
        ])
            ->whereIn('group_name',
                [
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
                    'custom_fields',
                    'discussions',
                    'notifications'
                ])
            ->get();

        $clientPermission = Permission::whereIn('name',
            [
                'manage_dashboard',
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
            ])
            ->get();

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

        $this->enableForeignKeys();
    }
}
