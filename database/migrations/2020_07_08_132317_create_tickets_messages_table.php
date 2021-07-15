<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->bigInteger('thread_id');
            $table->longText('message');
            $table->bigInteger('sender');
            $table->bigInteger('receiver');
            $table->bigInteger('sender_is_admin')->default(0);
            $table->integer('is_seen')->default(0);
            $table->integer('is_seen_by_admin')->default(0);
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
        Schema::dropIfExists('tickets_messages');
    }
}
