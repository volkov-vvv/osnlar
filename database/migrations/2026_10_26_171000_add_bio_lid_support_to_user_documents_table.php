<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioLidSupportToUserDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
            $table->unsignedBigInteger('bio_lid_id')->nullable()->after('order_id');
            $table->index('bio_lid_id');
        });
    }

    public function down()
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropIndex(['bio_lid_id']);
            $table->dropColumn('bio_lid_id');
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
        });
    }
}
