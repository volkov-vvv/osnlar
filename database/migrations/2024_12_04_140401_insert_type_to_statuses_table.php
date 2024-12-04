<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertTypeToStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            DB::table('statuses')->insert(
                array(
                    'title' => 'Ожидает оплаты',
                    'code' => 'waiting-payment',
                    'type' => 'order',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                )
            );

            DB::table('statuses')->insert(
                array(
                    'title' => 'Разрешена оплата',
                    'code' => 'payment-allowed',
                    'type' => 'order',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                )
            );

            DB::table('statuses')->insert(
                array(
                    'title' => 'Оплачен',
                    'code' => 'paid',
                    'type' => 'order',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                )
            );

            DB::table('statuses')->insert(
                array(
                    'title' => 'Отменен',
                    'code' => 'сancelled',
                    'type' => 'order',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                )
            );
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
