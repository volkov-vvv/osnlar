<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToLidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lids', function (Blueprint $table) {
            $table->index(['course_id', 'region_id', 'status_id', 'category_id', 'agent_id', 'responsible_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lids', function (Blueprint $table) {
            $table->dropIndex(['course_id', 'region_id', 'status_id', 'category_id', 'agent_id', 'responsible_id']);
        });
    }
}
