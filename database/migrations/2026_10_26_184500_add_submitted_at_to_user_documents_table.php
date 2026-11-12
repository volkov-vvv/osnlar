<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmittedAtToUserDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });
    }
}
