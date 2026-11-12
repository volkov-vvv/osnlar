<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('contract_phrase')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        $now = now();

        DB::table('projects')->insert([
            [
                'title' => 'Содействие занятости (СЗ)',
                'contract_phrase' => 'в рамках реализации Федерального проекта «Содействие занятости» национального проекта «Кадры»',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Активные меры содействия занятости (АМСЗ)',
                'contract_phrase' => 'в рамках реализации Федерального проекта «Активные меры содействия занятости» национального проекта «Кадры»',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Профессии будущего',
                'contract_phrase' => 'в рамках реализации проекта «Профессии будущего»',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Биоэкономика',
                'contract_phrase' => 'в рамках реализации национального проекта «Технологическое обеспечение биоэкономики»',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Код будущего',
                'contract_phrase' => 'в рамках реализации Федерального проекта «Кадры для цифровой трансформации» национального проекта «Экономика данных и цифровая трансформация государства»',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
