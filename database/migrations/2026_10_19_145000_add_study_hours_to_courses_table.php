<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'study_hours')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedSmallInteger('study_hours')->nullable()->after('study_weeks');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('courses', 'study_hours')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('study_hours');
        });
    }
};
