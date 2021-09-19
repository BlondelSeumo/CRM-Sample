<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('commentable');
            $table->longText('comment_body');
            $table->foreignId('commented_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');
            $table->foreignId('comment_id_of_reply')
                ->nullable()
                ->constrained('discussions');
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
        Schema::dropIfExists('discussions');
    }
}
