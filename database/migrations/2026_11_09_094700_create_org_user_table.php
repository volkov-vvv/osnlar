<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgUserTable extends Migration
{
    public function up()
    {
        Schema::create('org_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->index('org_id');
            $table->index('user_id');
            $table->unique(['org_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_user');
    }
}
