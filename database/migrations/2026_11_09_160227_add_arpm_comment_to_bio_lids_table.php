<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->text('arpm_comment')->nullable()->after('arpm_status');
        });
    }

    public function down(): void
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->dropColumn('arpm_comment');
        });
    }
};
