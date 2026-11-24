<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->string('payment_type', 32)->default('hourly')->after('work_types');
        });
    }

    public function down(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });
    }
};
