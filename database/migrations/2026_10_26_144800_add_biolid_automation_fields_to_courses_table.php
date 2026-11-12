<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiolidAutomationFieldsToCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('biolid_study_group_id')->nullable()->after('bioeconomy');
            $table->boolean('biolid_auto_create_user')->default(false)->after('biolid_study_group_id');
            $table->boolean('biolid_send_confirmation_email')->default(true)->after('biolid_auto_create_user');
            $table->boolean('biolid_send_credentials_email')->default(false)->after('biolid_send_confirmation_email');

            $table->foreign('biolid_study_group_id')
                ->references('id')
                ->on('study_groups')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['biolid_study_group_id']);
            $table->dropColumn([
                'biolid_study_group_id',
                'biolid_auto_create_user',
                'biolid_send_confirmation_email',
                'biolid_send_credentials_email',
            ]);
        });
    }
}
