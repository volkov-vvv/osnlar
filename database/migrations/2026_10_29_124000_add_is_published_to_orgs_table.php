<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPublishedToOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orgs', function (Blueprint $table) {
            if (! Schema::hasColumn('orgs', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('source');
            }
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
            if (Schema::hasColumn('orgs', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
}
