<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'study_weeks')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedSmallInteger('study_weeks')->nullable()->after('study_form');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('courses', 'study_weeks')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('study_weeks');
        });
    }
};
