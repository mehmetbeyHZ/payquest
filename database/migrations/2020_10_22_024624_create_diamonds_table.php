<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiamondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamonds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->integer('value');
            $table->integer('type');
            $table->string('diamond_token');
            $table->integer('diamond_token_confirmed')->default(0);
            $table->index(['user_id','diamond_token'],'user_id_and_diamond_token');
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
        Schema::dropIfExists('diamonds');
    }
}
