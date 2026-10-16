<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaxSentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('max_sent_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id')->nullable()->index();
            $table->string('message_id', 255)->nullable()->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('lid_id')->nullable()->index();
            $table->longText('text')->nullable();
            $table->unsignedBigInteger('max_user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('max_sent_messages');
    }
}
