<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppointmentLetterToOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orgs', function (Blueprint $table) {
            if (! Schema::hasColumn('orgs', 'appointment_letter')) {
                $table->string('appointment_letter')->nullable()->after('inn');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orgs', function (Blueprint $table) {
            if (Schema::hasColumn('orgs', 'appointment_letter')) {
                $table->dropColumn('appointment_letter');
            }
        });
    }
}
