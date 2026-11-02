<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassportFieldsToBioLidsTable extends Migration
{
    public function up()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->string('passport_series', 10)->nullable()->after('residence_address');
            $table->string('passport_number', 20)->nullable()->after('passport_series');
            $table->string('passport_issued_by')->nullable()->after('passport_number');
            $table->date('passport_issued_at')->nullable()->after('passport_issued_by');
            $table->string('passport_department_code', 20)->nullable()->after('passport_issued_at');
        });
    }

    public function down()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->dropColumn([
                'passport_series',
                'passport_number',
                'passport_issued_by',
                'passport_issued_at',
                'passport_department_code',
            ]);
        });
    }
}
