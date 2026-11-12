<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataSubmittedAtToBioLidsTable extends Migration
{
    public function up()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->timestamp('data_submitted_at')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->dropColumn('data_submitted_at');
        });
    }
}
