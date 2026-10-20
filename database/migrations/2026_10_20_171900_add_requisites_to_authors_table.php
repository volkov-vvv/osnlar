<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequisitesToAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('passport_series', 10)->nullable()->after('phone');
            $table->string('passport_number', 20)->nullable()->after('passport_series');
            $table->string('passport_issued_by')->nullable()->after('passport_number');
            $table->date('passport_issued_at')->nullable()->after('passport_issued_by');
            $table->string('passport_department_code', 20)->nullable()->after('passport_issued_at');
            $table->string('inn', 12)->nullable()->after('passport_department_code');
            $table->text('registration_address')->nullable()->after('inn');
            $table->string('bank_account', 34)->nullable()->after('registration_address');
            $table->string('bank_name')->nullable()->after('bank_account');
            $table->string('bank_bik', 20)->nullable()->after('bank_name');
            $table->string('bank_correspondent_account', 34)->nullable()->after('bank_bik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn([
                'passport_series',
                'passport_number',
                'passport_issued_by',
                'passport_issued_at',
                'passport_department_code',
                'inn',
                'registration_address',
                'bank_account',
                'bank_name',
                'bank_bik',
                'bank_correspondent_account',
            ]);
        });
    }
}
