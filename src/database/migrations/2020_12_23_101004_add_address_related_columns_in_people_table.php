<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressRelatedColumnsInPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            //
            $table->foreignId('country_id')
                ->nullable()
                ->references('id')
                ->on('countries')
                ->onDelete('SET NULL');
            $table->text('area')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {

            $table->dropColumn('country');
            $table->dropColumn('area');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('zip_code');
        });
    }
}
