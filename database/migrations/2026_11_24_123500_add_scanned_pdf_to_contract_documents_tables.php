<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('scanned_pdf')->nullable()->after('status');
        });

        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->string('scanned_pdf')->nullable()->after('status');
        });

        Schema::table('contract_reports', function (Blueprint $table) {
            $table->string('scanned_pdf')->nullable()->after('status');
        });

        Schema::table('contract_acts', function (Blueprint $table) {
            $table->string('scanned_pdf')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('scanned_pdf');
        });

        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->dropColumn('scanned_pdf');
        });

        Schema::table('contract_reports', function (Blueprint $table) {
            $table->dropColumn('scanned_pdf');
        });

        Schema::table('contract_acts', function (Blueprint $table) {
            $table->dropColumn('scanned_pdf');
        });
    }
};
