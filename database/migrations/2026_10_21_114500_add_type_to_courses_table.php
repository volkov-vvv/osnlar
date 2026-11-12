<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'type')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->string('type', 64)->nullable()->after('company_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('courses', 'type')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
