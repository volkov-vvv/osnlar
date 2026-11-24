<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('contract_reports', 'kind')) {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->string('kind', 32)->default('main')->after('assignment_id');
            });
        }

        if (! Schema::hasColumn('contract_reports', 'successful_students')) {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->json('successful_students')->nullable()->after('lesson_hours');
            });
        }

        // FK использует unique(assignment_id) — сначала снимаем FK, потом unique.
        Schema::table('contract_reports', function (Blueprint $table) {
            $table->dropForeign(['assignment_id']);
        });

        try {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->dropUnique(['assignment_id']);
            });
        } catch (\Throwable) {
            // уже снят
        }

        Schema::table('contract_reports', function (Blueprint $table) {
            $table->foreign('assignment_id')->references('id')->on('contract_assignments')->cascadeOnDelete();
        });

        $hasComposite = false;
        foreach (Schema::getConnection()->select('SHOW INDEX FROM contract_reports') as $index) {
            if ($index->Key_name === 'contract_reports_assignment_id_kind_unique') {
                $hasComposite = true;
                break;
            }
        }

        if (! $hasComposite) {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->unique(['assignment_id', 'kind']);
            });
        }

        $hasKindIndex = false;
        foreach (Schema::getConnection()->select('SHOW INDEX FROM contract_reports') as $index) {
            if ($index->Key_name === 'contract_reports_kind_index') {
                $hasKindIndex = true;
                break;
            }
        }
        if (! $hasKindIndex) {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->index('kind');
            });
        }

        if (! Schema::hasColumn('contract_acts', 'kind')) {
            Schema::table('contract_acts', function (Blueprint $table) {
                $table->string('kind', 32)->default('main')->after('assignment_id');
                $table->index('kind');
            });
        }
    }

    public function down(): void
    {
        Schema::table('contract_reports', function (Blueprint $table) {
            $table->dropForeign(['assignment_id']);
        });

        try {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->dropUnique(['assignment_id', 'kind']);
            });
        } catch (\Throwable) {
        }

        try {
            Schema::table('contract_reports', function (Blueprint $table) {
                $table->dropIndex(['kind']);
            });
        } catch (\Throwable) {
        }

        Schema::table('contract_reports', function (Blueprint $table) {
            $table->dropColumn(['kind', 'successful_students']);
            $table->unique('assignment_id');
            $table->foreign('assignment_id')->references('id')->on('contract_assignments')->cascadeOnDelete();
        });

        if (Schema::hasColumn('contract_acts', 'kind')) {
            Schema::table('contract_acts', function (Blueprint $table) {
                $table->dropIndex(['kind']);
                $table->dropColumn('kind');
            });
        }
    }
};
