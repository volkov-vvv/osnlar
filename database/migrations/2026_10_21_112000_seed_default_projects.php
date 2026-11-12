<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedDefaultProjects extends Migration
{
    private array $projects = [
        [
            'title' => 'Содействие занятости (СЗ)',
            'contract_phrase' => 'в рамках реализации Федерального проекта «Содействие занятости» национального проекта «Кадры»',
        ],
        [
            'title' => 'Активные меры содействия занятости (АМСЗ)',
            'contract_phrase' => 'в рамках реализации Федерального проекта «Активные меры содействия занятости» национального проекта «Кадры»',
        ],
        [
            'title' => 'Профессии будущего',
            'contract_phrase' => 'в рамках реализации проекта «Профессии будущего»',
        ],
        [
            'title' => 'Биоэкономика',
            'contract_phrase' => 'в рамках реализации национального проекта «Технологическое обеспечение биоэкономики»',
        ],
        [
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
        $hasPhrase = Schema::hasColumn('projects', 'contract_phrase');

        foreach ($this->projects as $project) {
            $exists = DB::table('projects')
                ->where('title', $project['title'])
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                continue;
            }

            $row = [
                'title' => $project['title'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasPhrase) {
                $row['contract_phrase'] = $project['contract_phrase'];
            }

            DB::table('projects')->insert($row);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $titles = array_column($this->projects, 'title');
        DB::table('projects')->whereIn('title', $titles)->delete();
    }
}
