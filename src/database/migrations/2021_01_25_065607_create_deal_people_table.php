<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_people', function (Blueprint $table) {
            $table->unsignedBigInteger('deal_id');
            $table->unsignedBigInteger('person_id');
            $table->foreign('deal_id')
                ->references('id')
                ->on("deals")
                ->onDelete('cascade');
            $table->foreign('person_id')
                ->references('id')
                ->on("people")
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_people');
    }
}
