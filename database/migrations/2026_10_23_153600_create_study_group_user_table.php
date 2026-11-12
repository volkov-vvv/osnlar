<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyGroupUserTable extends Migration
{
    public function up()
    {
        Schema::create('study_group_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->index('study_group_id');
            $table->index('user_id');
            $table->unique(['study_group_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('study_group_user');
    }
}
