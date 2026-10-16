<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'starts_at')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->date('starts_at')->nullable()->after('years');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('courses', 'starts_at')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('starts_at');
        });
    }
};
