<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission', function (Blueprint $table) {
            $table->id('mission_id');
            $table->integer('mission_level');
            $table->decimal('mission_value')->default(0);
            $table->integer('mission_second')->default(0);
            $table->integer('mission_xp')->default(0);
            $table->bigInteger('mission_take_limit')->default(0);
            $table->integer('is_question')->default(0);
            $table->longText('mission_question')->nullable();
            $table->integer('type')->default(0);
            $table->string('intent_link')->nullable();
            $table->longText('mission_question_answers')->nullable();
            $table->integer('correct_index')->nullable();
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('mission');
    }
}
