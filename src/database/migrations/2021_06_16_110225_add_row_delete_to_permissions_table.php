<?php

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowDeleteToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Role::whereNotIn('name', ['App Admin', 'Manager', 'Agent', 'Client'])->delete();
            Permission::whereIn('name',
                [
                 'view_lead_groups',
                 'create_lead_groups',
                 'update_lead_groups',
                 'delete_lead_groups',
                 'view_deal_report',
                 'view_deal_report_chart',
                 'view_deal_report_details',
                 'view_chart_proposals',
                 'view_data_table_proposals'
                ])
                ->delete();
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
