<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrainingDatesToStudyGroupsTable extends Migration
{
    public function up()
    {
        Schema::table('study_groups', function (Blueprint $table) {
            $table->date('starts_at')->nullable()->after('description');
            $table->date('ends_at')->nullable()->after('starts_at');
        });
    }

    public function down()
    {
        Schema::table('study_groups', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'ends_at']);
        });
    }
}
