<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggableTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            // $table->bigIncrements('tag_id');

            $table->foreignId('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
            $table->nullableMorphs('taggable');
            $table->unique(['tag_id', 'taggable_type', 'taggable_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('taggable');
    }
}
