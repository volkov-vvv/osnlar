<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgs', function (Blueprint $table) {
            $table->id();
            $table->string('organization_title');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();


            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->boolean('politic')->default(0);

            $table->unsignedBigInteger('status_id')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('orgs');
    }
}
