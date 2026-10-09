<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminFieldsToOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orgs', function (Blueprint $table) {
            $table->string('organization_full_title')->nullable()->after('organization_title');
            $table->string('additional_phone')->nullable()->after('phone');
            $table->string('additional_email')->nullable()->after('email');
            $table->string('address')->nullable()->after('additional_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orgs', function (Blueprint $table) {
            $table->dropColumn([
                'organization_full_title',
                'additional_phone',
                'additional_email',
                'address',
            ]);
        });
    }
}
