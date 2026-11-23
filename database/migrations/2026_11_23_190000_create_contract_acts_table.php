<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_acts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained('contract_assignments')->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->string('status', 32)->default('draft');
            $table->date('issued_at')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('contract_id');
            $table->index('assignment_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_acts');
    }
};
