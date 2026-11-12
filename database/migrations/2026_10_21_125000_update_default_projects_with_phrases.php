<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDefaultProjectsWithPhrases extends Migration
{
    /**
     * Canonical default projects and contract phrases.
     */
    private array $projects = [
        [
            'match_titles' => ['Содействие занятости (СЗ)'],
            'title' => 'Содействие занятости (СЗ)',
            'contract_phrase' => 'в рамках реализации Федерального проекта «Содействие занятости» национального проекта «Кадры»',
        ],
        [
            'match_titles' => [
                'Активные меры содействия занятости',
                'Активные меры содействия занятости (АМСЗ)',
            ],
            'title' => 'Активные меры содействия занятости (АМСЗ)',
            'contract_phrase' => 'в рамках реализации Федерального проекта «Активные меры содействия занятости» национального проекта «Кадры»',
        ],
        [
            'match_titles' => ['Профессии будущего'],
            'title' => 'Профессии будущего',
            'contract_phrase' => 'в рамках реализации проекта «Профессии будущего»',
        ],
        [
            'match_titles' => ['Биоэкономика'],
            'title' => 'Биоэкономика',
            'contract_phrase' => 'в рамках реализации национального проекта «Технологическое обеспечение биоэкономики»',
        ],
        [
            'match_titles' => ['Код будущего'],
            'title' => 'Код будущего',
            'contract_phrase' => 'в рамках реализации Федерального проекта «Кадры для цифровой трансформации» национального проекта «Экономика данных и цифровая трансформация государства»',
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = now();

        foreach ($this->projects as $project) {
            $row = DB::table('projects')
                ->whereNull('deleted_at')
                ->whereIn('title', $project['match_titles'])
                ->orderBy('id')
                ->first();

            if ($row) {
                DB::table('projects')
                    ->where('id', $row->id)
                    ->update([
                        'title' => $project['title'],
                        'contract_phrase' => $project['contract_phrase'],
                        'updated_at' => $now,
                    ]);

                continue;
            }

            DB::table('projects')->insert([
                'title' => $project['title'],
                'contract_phrase' => $project['contract_phrase'],
                'created_at' => $now,
                'updated_at' => $now,
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
        // Keep data; no destructive rollback.
    }
}
