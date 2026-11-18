<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioLidIdToMaxSentMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('max_sent_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('bio_lid_id')->nullable()->after('lid_id')->index();
        });
    }

    public function down()
    {
        Schema::table('max_sent_messages', function (Blueprint $table) {
            $table->dropIndex(['bio_lid_id']);
            $table->dropColumn('bio_lid_id');
        });
    }
}
