<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtendedNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extended_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('contextable_type');
            $table->unsignedBigInteger('contextable_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('audience');
            $table->longText('message')->nullable();
            $table->string('readers_id')->default('0');
            $table->string('read_at')->nullable();
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
        Schema::dropIfExists('extended_notifications');
    }
}
