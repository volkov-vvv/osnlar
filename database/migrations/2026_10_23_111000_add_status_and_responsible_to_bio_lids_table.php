<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndResponsibleToBioLidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->default(1)->after('politic');
            $table->unsignedBigInteger('responsible_id')->nullable()->after('status_id');
            $table->index('status_id');
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
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->dropIndex(['status_id']);
            $table->dropIndex(['responsible_id']);
            $table->dropColumn(['status_id', 'responsible_id']);
        });
    }
}
