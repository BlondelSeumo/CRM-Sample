<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_stage', function (Blueprint $table) {
            $table->unsignedBigInteger('deal_id');

            $table->unsignedBigInteger('stage_id');

            $table->primary(['deal_id', 'stage_id']);

            $table->foreign('deal_id')
                ->references('id')
                ->on('deals')
                ->onDelete('cascade');

            $table->foreign('stage_id')
                ->references('id')
                ->on('stages')
                ->onDelete('cascade');

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
        Schema::dropIfExists('deal_stage');
    }
}
