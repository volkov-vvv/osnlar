<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddShortTitleToProjectsTable extends Migration
{
    private array $shortTitles = [
        'Содействие занятости (СЗ)' => 'СЗ',
        'Активные меры содействия занятости (АМСЗ)' => 'АМСЗ',
        'Активные меры содействия занятости' => 'АМСЗ',
        'Профессии будущего' => 'ПБ',
        'Биоэкономика' => 'БИО',
        'Код будущего' => 'КБ',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('projects', 'short_title')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('short_title', 5)->nullable()->after('title');
            });
        }

        foreach ($this->shortTitles as $title => $shortTitle) {
            DB::table('projects')
                ->where('title', $title)
                ->whereNull('deleted_at')
                ->update([
                    'short_title' => mb_strtoupper($shortTitle, 'UTF-8'),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('projects', 'short_title')) {
            return;
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('short_title');
        });
    }
}
