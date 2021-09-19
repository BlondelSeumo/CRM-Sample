<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->foreignId('status_id')->nullable()->references('id')->on('statuses')->onDelete('SET NULL');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->foreignId('contact_type_id')->nullable()->references('id')->on('contact_types')->onDelete('SET NULL');
            $table->morphs('contextable');
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
        Schema::dropIfExists('events');
    }
}
