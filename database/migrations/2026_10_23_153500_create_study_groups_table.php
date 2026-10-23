<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('study_groups');
    }
}
