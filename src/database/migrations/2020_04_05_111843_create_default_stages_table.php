<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('probability')->nullable()->default(100);
            $table->integer('priority')->default(0);
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('default_stages');
    }
}
