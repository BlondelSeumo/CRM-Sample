<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->bigInteger('value')->default(0);
            $table->unsignedBigInteger('person_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();

            $table->foreignId('pipeline_id')
                ->references('id')
                ->on('pipelines')
                ->onDelete('cascade');

            $table->foreignId('stage_id')
                ->references('id')
                ->on('stages');

            $table->foreignId('lost_reason_id')
                ->nullable()
                ->references('id')
                ->on('lost_reasons');

            $table->foreignId('status_id')
                ->nullable()
                ->references('id')
                ->on('statuses')
                ->onDelete('SET NULL');

            $table->foreignId('created_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->foreignId('owner_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->dateTime('expired_at')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('deals');
    }
}
