<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEducationDetailsToBioLidsTable extends Migration
{
    public function up()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->string('education_organization')->nullable()->after('education');
            $table->string('education_qualification')->nullable()->after('education_organization');
            $table->string('diploma_series_number', 100)->nullable()->after('education_qualification');
            $table->date('diploma_issued_at')->nullable()->after('diploma_series_number');
        });
    }

    public function down()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->dropColumn([
                'education_organization',
                'education_qualification',
                'diploma_series_number',
                'diploma_issued_at',
            ]);
        });
    }
}
