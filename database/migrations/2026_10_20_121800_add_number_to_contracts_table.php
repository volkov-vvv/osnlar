<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNumberToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('number')->nullable()->after('id');
        });

        $contracts = DB::table('contracts')->select('id', 'created_at')->get();
        foreach ($contracts as $contract) {
            $year = $contract->created_at
                ? date('Y', strtotime($contract->created_at))
                : date('Y');

            DB::table('contracts')
                ->where('id', $contract->id)
                ->update(['number' => $contract->id.'/'.$year.'ДПУ']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('number');
        });
    }
}
