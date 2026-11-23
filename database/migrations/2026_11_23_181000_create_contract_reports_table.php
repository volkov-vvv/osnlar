<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained('contract_assignments')->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->string('status', 32)->default('draft');
            $table->date('issued_at')->nullable();
            $table->date('starts_at');
            $table->date('ends_at');
            $table->text('lesson_url')->nullable();
            $table->decimal('lesson_hours', 8, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique('assignment_id');
            $table->index('contract_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_reports');
    }
};
