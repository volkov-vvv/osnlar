<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBioLidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bio_lids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->index('course_id');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 16)->nullable();
            $table->string('snils', 14)->nullable();
            $table->string('citizenship')->nullable();
            $table->text('residence_address')->nullable();
            $table->text('education')->nullable();
            $table->string('organization_title')->nullable();
            $table->string('organization_inn', 12)->nullable();
            $table->string('position')->nullable();
            $table->string('email');
            $table->string('phone', 32);
            $table->boolean('politic')->default(false);
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
        Schema::dropIfExists('bio_lids');
    }
}
