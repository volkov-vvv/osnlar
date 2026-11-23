<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->json('work_type_codes')->nullable()->after('work_types');
            $table->string('program_url')->nullable()->after('work_type_codes');
            $table->string('schedule_url')->nullable()->after('program_url');
            $table->string('notebook_url')->nullable()->after('schedule_url');
        });
    }

    public function down(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->dropColumn(['work_type_codes', 'program_url', 'schedule_url', 'notebook_url']);
        });
    }
};
