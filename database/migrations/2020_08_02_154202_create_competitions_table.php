<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id('competition');
            $table->string('competition_title');
            $table->text('competition_description');
            $table->text('competition_image')->nullable();
            $table->decimal('registration_fee');
            $table->decimal('award');
            $table->integer('can_register');
            $table->timestamp('start_date');
            $table->timestamp('last_register_date',0)->nullable();
            $table->integer('total_winner');
            $table->integer('max_users');
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
        Schema::dropIfExists('competitions');
    }
}
