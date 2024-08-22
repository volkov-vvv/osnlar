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
            $table->index('course_id');
            $table->index('region_id');
            $table->index('status_id');
            $table->index('category_id');
            $table->index('agent_id');
            $table->index('responsible_id');
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
            $table->dropIndex('course_id');
            $table->dropIndex('region_id');
            $table->dropIndex('status_id');
            $table->dropIndex('category_id');
            $table->dropIndex('agent_id');
            $table->dropIndex('responsible_id');
        });
    }
}
