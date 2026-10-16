<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'study_form')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->string('study_form', 32)->nullable()->after('starts_at');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('courses', 'study_form')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('study_form');
        });
    }
};
