<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'default_status_id')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            // No DB FK: statuses table is MyISAM in this environment.
            $table->unsignedBigInteger('default_status_id')->nullable()->after('years');
            $table->index('default_status_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('courses', 'default_status_id')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['default_status_id']);
            $table->dropColumn('default_status_id');
        });
    }
};
