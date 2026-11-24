<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->boolean('per_student_enabled')->default(false)->after('payment_type');
            $table->decimal('per_student_amount', 12, 2)->nullable()->after('hourly_rate');
        });

        // Старые записи «только за слушателя» → отдельная сумма + флаг.
        $rows = DB::table('contract_assignments')
            ->where('payment_type', 'per_student')
            ->get(['id', 'hourly_rate']);

        foreach ($rows as $row) {
            DB::table('contract_assignments')
                ->where('id', $row->id)
                ->update([
                    'payment_type' => 'none',
                    'per_student_enabled' => true,
                    'per_student_amount' => $row->hourly_rate,
                    'hourly_rate' => 0,
                ]);
        }
    }

    public function down(): void
    {
        $rows = DB::table('contract_assignments')
            ->where('per_student_enabled', true)
            ->where('payment_type', 'none')
            ->get(['id', 'hourly_rate', 'per_student_amount']);

        foreach ($rows as $row) {
            DB::table('contract_assignments')
                ->where('id', $row->id)
                ->update([
                    'payment_type' => 'per_student',
                    'hourly_rate' => $row->per_student_amount ?? $row->hourly_rate,
                ]);
        }

        Schema::table('contract_assignments', function (Blueprint $table) {
            $table->dropColumn(['per_student_enabled', 'per_student_amount']);
        });
    }
};
