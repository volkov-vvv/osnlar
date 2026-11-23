<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->text('custom_work_type')->nullable()->after('notebook_url');
        });
    }

    public function down(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->dropColumn('custom_work_type');
        });
    }
};
