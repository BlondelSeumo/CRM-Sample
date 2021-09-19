<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('activity_type_id')
                ->nullable()
                ->references('id')
                ->on('activity_types')
                ->onDelete('SET NULL');

            $table->nullableMorphs('contextable');

            $table->foreignId('created_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->foreignId('status_id')
                ->nullable()
                ->references('id')
                ->on('statuses')
                ->onDelete('SET NULL');

            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
