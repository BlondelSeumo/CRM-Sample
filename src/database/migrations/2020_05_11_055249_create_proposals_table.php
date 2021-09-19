<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('subject');

            $table->longText('content');

            $table->foreignId('status_id')
                ->nullable()
                ->references('id')
                ->on('statuses')
                ->onDelete('SET NULL');

            $table->foreignId('deal_id')
                ->nullable()
                ->references('id')
                ->on('deals')
                ->onDelete('cascade');

            $table->foreignId('owner_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->foreignId('created_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->dateTime('sent_at')->nullable();

            $table->dateTime('accepted_at')->nullable();

            $table->timestamps();

            $table->dateTime('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
}
