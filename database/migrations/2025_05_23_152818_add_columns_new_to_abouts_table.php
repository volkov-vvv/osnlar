<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsNewToAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->text('about_admissions')->nullable()->after('about_tech');
            $table->text('about_catering')->nullable()->after('about_admissions');
            $table->text('about_global')->nullable()->after('about_catering');
            $table->text('about_financial')->nullable()->after('about_global');
            $table->text('about_tuition')->nullable()->after('about_financial');
            $table->text('about_education')->nullable()->after('about_tuition');
            $table->text('about_grants')->nullable()->after('about_education');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn('about_admissions');
            $table->dropColumn('about_catering');
            $table->dropColumn('about_global');
            $table->dropColumn('about_financial');
            $table->dropColumn('about_tuition');
            $table->dropColumn('about_education');
            $table->dropColumn('about_grants');
        });
    }
}
