<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUserIdToBioLidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('bio_lids', 'user_id')) {
            Schema::table('bio_lids', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('responsible_id');
            });
        }

        $indexes = collect(DB::select('SHOW INDEX FROM bio_lids WHERE Column_name = ?', ['user_id']))
            ->pluck('Key_name')
            ->unique()
            ->all();

        if (! in_array('bio_lids_user_id_index', $indexes, true)) {
            Schema::table('bio_lids', function (Blueprint $table) {
                $table->index('user_id');
            });
        }

        // FK на users не создаём: таблица users может быть MyISAM.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('bio_lids', 'user_id')) {
            return;
        }

        Schema::table('bio_lids', function (Blueprint $table) {
            $indexes = collect(DB::select('SHOW INDEX FROM bio_lids WHERE Column_name = ?', ['user_id']))
                ->pluck('Key_name')
                ->unique()
                ->all();

            if (in_array('bio_lids_user_id_index', $indexes, true)) {
                $table->dropIndex(['user_id']);
            }

            $table->dropColumn('user_id');
        });
    }
}
