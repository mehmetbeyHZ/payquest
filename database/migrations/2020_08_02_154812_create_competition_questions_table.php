<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_questions', function (Blueprint $table) {
            $table->id('question');
            $table->longText('question_text');
            $table->longText('question_answers');
            $table->integer('question_time');
            $table->timestamp('question_viewed_date',0)->nullable();
            $table->integer('correct_index');
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
        Schema::dropIfExists('competition_questions');
    }
}
