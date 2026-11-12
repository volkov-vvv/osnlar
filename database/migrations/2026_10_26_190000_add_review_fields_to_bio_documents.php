<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddReviewFieldsToBioDocuments extends Migration
{
    public function up()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->string('data_review_status', 20)->nullable()->after('data_submitted_at');
            $table->text('data_review_comment')->nullable()->after('data_review_status');
            $table->timestamp('data_reviewed_at')->nullable()->after('data_review_comment');
            $table->unsignedBigInteger('data_reviewed_by')->nullable()->after('data_reviewed_at');
            $table->index('data_review_status');
        });

        Schema::table('user_documents', function (Blueprint $table) {
            $table->string('review_status', 20)->nullable()->after('submitted_at');
            $table->text('review_comment')->nullable()->after('review_status');
            $table->timestamp('reviewed_at')->nullable()->after('review_comment');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
            $table->index('review_status');
        });

        // Уже отправленные на проверку считаем «На проверке».
        DB::table('bio_lids')
            ->whereNotNull('data_submitted_at')
            ->whereNull('data_review_status')
            ->update(['data_review_status' => 'pending']);

        DB::table('user_documents')
            ->whereNotNull('submitted_at')
            ->whereNull('review_status')
            ->update(['review_status' => 'pending']);
    }

    public function down()
    {
        Schema::table('bio_lids', function (Blueprint $table) {
            $table->dropIndex(['data_review_status']);
            $table->dropColumn([
                'data_review_status',
                'data_review_comment',
                'data_reviewed_at',
                'data_reviewed_by',
            ]);
        });

        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropIndex(['review_status']);
            $table->dropColumn([
                'review_status',
                'review_comment',
                'reviewed_at',
                'reviewed_by',
            ]);
        });
    }
}
