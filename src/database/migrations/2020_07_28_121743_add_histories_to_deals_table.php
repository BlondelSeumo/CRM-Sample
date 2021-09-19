<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHistoriesToDealsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->longText('histories')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('histories');
        });
    }
}
