<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_organization', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('job_title')->nullable()->default('No Title');

           // $table->primary(['person_id', 'organization_id']);

            $table->foreign('person_id')
                ->references('id')
                ->on("people")
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('id')
                ->on("organizations")
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
        Schema::dropIfExists('person_organization');
    }
}
