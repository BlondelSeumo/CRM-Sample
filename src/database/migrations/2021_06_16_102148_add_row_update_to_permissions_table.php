<?php

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRowUpdateToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
//            $getPermission = Permission::whereIn('group_name', ['lead_groups'])
//                ->get()
//                ->toArray();
//
//            Permission::where('id', $getPermission[0]['id'])->update([
//                'name' => 'view_types',
//            ]);
//            Permission::where('id', $getPermission[1]['id'])->update([
//                'name' => 'create_types',
//            ]);
//            Permission::where('id', $getPermission[2]['id'])->update([
//                'name' => 'update_types',
//            ]);
//            Permission::where('id', $getPermission[3]['id'])->update([
//                'name' => 'delete_types',
//            ]);

            $users = User::whereDoesntHave('roles', function (Builder $query) {
                $query->where('is_admin', 1);
            })->get();

            $role = Role::where('name', 'Agent')->first();

            foreach ($users as $user) {
                DB::table('role_user')->insert([
                    'user_id' => $user['id'],
                    'role_id' => $role->id
                ]);
            }

//            // Deal
//
//            $dealPermission = Permission::where('name', 'view_deal_report')->first();
//
//            Permission::where('id', $dealPermission->id)->update([
//                'name' => 'deal_reports',
//            ]);
//
//            // Proposal
//
//            $proposalPermission = Permission::where('name', 'view_chart_proposals')->first();
//
//            Permission::where('id', $proposalPermission->id)->update([
//                'name' => 'proposal_reports',
//            ]);


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
