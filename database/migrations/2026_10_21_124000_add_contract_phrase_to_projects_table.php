<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddContractPhraseToProjectsTable extends Migration
{
    private array $phrases = [
        'Содействие занятости (СЗ)' => 'в рамках реализации Федерального проекта «Содействие занятости» национального проекта «Кадры»',
        'Активные меры содействия занятости' => 'в рамках реализации Федерального проекта «Активные меры содействия занятости» национального проекта «Кадры»',
        'Активные меры содействия занятости (АМСЗ)' => 'в рамках реализации Федерального проекта «Активные меры содействия занятости» национального проекта «Кадры»',
        'Профессии будущего' => 'в рамках реализации проекта «Профессии будущего»',
        'Биоэкономика' => 'в рамках реализации национального проекта «Технологическое обеспечение биоэкономики»',
        'Код будущего' => 'в рамках реализации Федерального проекта «Кадры для цифровой трансформации» национального проекта «Экономика данных и цифровая трансформация государства»',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('projects', 'contract_phrase')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->text('contract_phrase')->nullable()->after('title');
            });
        }

        foreach ($this->phrases as $title => $phrase) {
            DB::table('projects')
                ->where('title', $title)
                ->whereNull('deleted_at')
                ->update([
                    'contract_phrase' => $phrase,
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
        if (! Schema::hasColumn('projects', 'contract_phrase')) {
            return;
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('contract_phrase');
        });
    }
}
