<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Type;
use Illuminate\Database\Seeder;
use App\Models\Core\Auth\Permission;
use Database\Seeders\Traits\DisableForeignKeys;

class PermissionTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Permission::query()->truncate();
        $appId = Type::findByAlias('app')->id;

        $core_permissions = [
            //settings

            [
                'name' => 'list_view_settings',
                'type_id' => $appId,
                'group_name' => 'settings',
            ],

            [
                'name' => 'view_settings',
                'type_id' => $appId,
                'group_name' => 'settings',
            ],
            [
                'name' => 'update_settings',
                'type_id' => $appId,
                'group_name' => 'settings',
            ],
//            [
//                'name' => 'change_settings_users',
//                'type_id' => $appId,
//                'group_name' => 'users',
//            ],
//            [
//                'name' => 'settings_list_users',
//                'type_id' => $appId,
//                'group_name' => 'users',
//            ],
            /*[
                'name' => 'change_password_users',
                'type_id' => $appId,
                'group_name' => 'users'
            ],*/
            /*  [
                'name' => 'change_profile_picture_users',
                'type_id' => $appId,
                'group_name' => 'users'
            ],*/
            [
                'name' => 'update_delivery_settings',
                'type_id' => $appId,
                'group_name' => 'settings',
            ],
            [
                'name' => 'view_delivery_settings',
                'type_id' => $appId,
                'group_name' => 'settings',
            ],


            //end of settings

            //Notifications Start
            [
                'name' => 'list_notifications',
                'type_id' => $appId,
                'group_name' => 'notifications',
            ],
            [
                'name' => 'view_notification_event',
                'type_id' => $appId,
                'group_name' => 'notifications',
            ],
            [
                'name' => 'update_notification_templates',
                'type_id' => $appId,
                'group_name' => 'notifications',
            ],
            [
                'name' => 'view_notification_channels',
                'type_id' => $appId,
                'group_name' => 'notifications',
            ],
            [
                'name' => 'manage_notification_audience',
                'type_id' => $appId,
                'group_name' => 'notifications',
            ],

//            [
//                'name' => 'view_notification_settings',
//                'type_id' => $appId,
//                'group_name' => 'notifications',
//            ],
//            [
//                'name' => 'update_notification_settings',
//                'type_id' => $appId,
//                'group_name' => 'notifications',
//            ],

            // End Notification

            // Custom Filed Start

           [
               'name' => 'view_custom_fields',
               'type_id' => $appId,
               'group_name' => 'custom_fields',
           ],
            [
                'name' => 'create_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields',
            ],
            [
                'name' => 'update_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields',
            ],
            [
                'name' => 'delete_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields',
            ],

            // Log Start

            [
                'name' => 'view_activity_logs',
                'type_id' => $appId,
                'group_name' => 'log',
            ],


            //User Start

            [
                'name' => 'invite_user',
                'type_id' => $appId,
                'group_name' => 'users',
            ],

            [
                'name' => 'view_users',
                'type_id' => $appId,
                'group_name' => 'users',
            ],
            [
                'name' => 'update_users',
                'type_id' => $appId,
                'group_name' => 'users',
            ],
            [
                'name' => 'delete_users',
                'type_id' => $appId,
                'group_name' => 'users',
            ],
            [
                'name' => 'attach_roles_users',
                'type_id' => $appId,
                'group_name' => 'users',
            ],
            [
                'name' => 'detach_roles_users',
                'type_id' => $appId,
                'group_name' => 'users',
            ],
            [
                'name' => 'user_active',
                'type_id' => $appId,
                'group_name' => 'users',
            ],
            [
                'name' => 'user_deactive',
                'type_id' => $appId,
                'group_name' => 'users',
            ],

//            [
//                'name' => 'create_users',
//                'type_id' => $appId,
//                'group_name' => 'users',
//            ],
//
//            [
//                'name' => 'cancel_user_invitation',
//                'type_id' => $appId,
//                'group_name' => 'users'
//            ],


            //end users


            // Role Start
            [
                'name' => 'view_roles',
                'type_id' => $appId,
                'group_name' => 'roles',
            ],
            [
                'name' => 'create_roles',
                'type_id' => $appId,
                'group_name' => 'roles',
            ],
            [
                'name' => 'update_roles',
                'type_id' => $appId,
                'group_name' => 'roles',
            ],
            [
                'name' => 'delete_roles',
                'type_id' => $appId,
                'group_name' => 'roles',
            ],
            [
                'name' => 'attach_users_to_roles',
                'type_id' => $appId,
                'group_name' => 'roles',
            ],

            // Permission Start

            [
                'name' => 'attach_permissions_roles',
                'type_id' => $appId,
                'group_name' => 'permissions',
            ],
            [
                'name' => 'detach_permissions_roles',
                'type_id' => $appId,
                'group_name' => 'permissions',
            ],

            // Dashboard Start

            [
                'name' => 'manage_dashboard',
                'type_id' => $appId,
                'group_name' => 'dashboard',
            ],

            [
                'name' => 'manage_public_access',
                'type_id' => $appId,
                'group_name' => 'public_access_behavior',
            ],
            [
                'name' => 'client_access',
                'type_id' => $appId,
                'group_name' => 'clients',
            ],
            [
                'name' => 'user_info_lead',
                'type_id' => $appId,
                'group_name' => 'clients'
            ],

            //Notification Templates
//            [
//                'name' => 'view_notification_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'create_notification_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'update_notification_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'delete_notification_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'attach_templates_notification_events',
//                'type_id' => $appId,
//                'group_name' => 'notification_events',
//            ],
//            [
//                'name' => 'detach_templates_notification_events',
//                'type_id' => $appId,
//                'group_name' => 'notification_events',
//            ],
            //            [
            //                'name' => 'update_corn_job_settings',
            //                'type_id' => $appId
            //            ],
            //            [
            //                'name' => 'view_corn_job_settings',
            //                'type_id' => $appId
            //            ],

//            [
//                'name' => 'view_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'create_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'update_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],
//            [
//                'name' => 'delete_templates',
//                'type_id' => $appId,
//                'group_name' => 'templates',
//            ],

        ];

        $permissions = array_merge(
            $core_permissions,
            include base_path('database/seeders/CRM/Permissions/CRMAppPermissions.php')
        );

        $this->enableForeignKeys();
        Permission::query()->insert($permissions);
    }
}
