<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertInitialDataToStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            DB::table('statuses')->insert([
                [
                    'title' => 'Документы загружены. 1 шаг',
                    'description' => 'Пользователь загрузил все документы для первого шага',
                    'code' => 'docs_first_step_done',
                    'type' => 'order',
                ],
                [
                    'title' => 'Документы проверены. 1 шаг',
                    'description' => 'Менеджер проверил документы для первого шага',
                    'code' => 'docs_first_step_confirm',
                    'type' => 'order',
                ],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            //
        });
    }
}
