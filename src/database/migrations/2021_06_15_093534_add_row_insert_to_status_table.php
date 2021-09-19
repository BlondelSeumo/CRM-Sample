<?php

use App\Models\Core\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowInsertToStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $status = Status::pluck('name')->toArray();
            $checkStatus = array_search(['status_rejected', 'status_no_reply'], $status);
            if ($checkStatus == false) {
                $statuses = [
                    [
                        'name' => 'status_rejected',
                        'type' => 'proposal',
                        'class' => 'danger',
                    ],
                    [
                        'name' => 'status_no_reply',
                        'type' => 'proposal',
                        'class' => 'info',
                    ]
                ];
                Status::query()->insert($statuses);
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
