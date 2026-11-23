<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->foreignId('study_group_id')->constrained('study_groups');
            $table->text('work_types');
            $table->decimal('hourly_rate', 12, 2);
            $table->date('starts_at');
            $table->date('ends_at');
            $table->date('issued_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['contract_id', 'number']);
            $table->index('study_group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_assignments');
    }
};
