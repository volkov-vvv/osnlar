<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioeconomyFieldsToOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orgs', function (Blueprint $table) {
            if (! Schema::hasColumn('orgs', 'inn')) {
                $table->string('inn', 12)->nullable()->after('organization_title');
            }
            if (! Schema::hasColumn('orgs', 'comment')) {
                $table->text('comment')->nullable()->after('politic');
            }
            if (! Schema::hasColumn('orgs', 'source')) {
                $table->string('source')->nullable()->after('comment');
            }
        });

        if (Schema::hasColumn('orgs', 'email')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->string('email')->nullable()->change();
            });
        }

        if (! Schema::hasTable('course_org')) {
            Schema::create('course_org', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id');
                $table->unsignedBigInteger('course_id');
                $table->timestamps();
                $table->unique(['org_id', 'course_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_org');

        Schema::table('orgs', function (Blueprint $table) {
            if (Schema::hasColumn('orgs', 'inn')) {
                $table->dropColumn('inn');
            }
            if (Schema::hasColumn('orgs', 'comment')) {
                $table->dropColumn('comment');
            }
            if (Schema::hasColumn('orgs', 'source')) {
                $table->dropColumn('source');
            }
        });
    }
}
