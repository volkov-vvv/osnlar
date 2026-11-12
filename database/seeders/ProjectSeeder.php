<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Проекты по умолчанию: название, краткое название и формулировка в договоре.
     */
    public static function defaults(): array
    {
        return [
            [
                'match_titles' => ['Содействие занятости (СЗ)'],
                'title' => 'Содействие занятости (СЗ)',
                'short_title' => 'СЗ',
                'contract_phrase' => 'в рамках реализации Федерального проекта «Содействие занятости» национального проекта «Кадры»',
            ],
            [
                'match_titles' => [
                    'Активные меры содействия занятости',
                    'Активные меры содействия занятости (АМСЗ)',
                ],
                'title' => 'Активные меры содействия занятости (АМСЗ)',
                'short_title' => 'АМСЗ',
                'contract_phrase' => 'в рамках реализации Федерального проекта «Активные меры содействия занятости» национального проекта «Кадры»',
            ],
            [
                'match_titles' => ['Профессии будущего'],
                'title' => 'Профессии будущего',
                'short_title' => 'ПБ',
                'contract_phrase' => 'в рамках реализации проекта «Профессии будущего»',
            ],
            [
                'match_titles' => ['Биоэкономика'],
                'title' => 'Биоэкономика',
                'short_title' => 'БИО',
                'contract_phrase' => 'в рамках реализации национального проекта «Технологическое обеспечение биоэкономики»',
            ],
            [
                'match_titles' => ['Код будущего'],
                'title' => 'Код будущего',
                'short_title' => 'КБ',
                'contract_phrase' => 'в рамках реализации Федерального проекта «Кадры для цифровой трансформации» национального проекта «Экономика данных и цифровая трансформация государства»',
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::defaults() as $project) {
            $existing = Project::query()
                ->where(function ($query) use ($project) {
                    $query->where('short_title', $project['short_title'])
                        ->orWhereIn('title', $project['match_titles']);
                })
                ->orderBy('id')
                ->first();

            $payload = [
                'title' => $project['title'],
                'short_title' => mb_strtoupper($project['short_title'], 'UTF-8'),
                'contract_phrase' => $project['contract_phrase'],
            ];

            if ($existing) {
                $existing->update($payload);
                continue;
            }

            Project::create($payload);
        }
    }
}
