<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id('payment_id');
            $table->bigInteger('user_id');
            $table->bigInteger('mission_id');
            $table->decimal('payment_value');
            $table->integer('payment_type');
            $table->text('payment_token')->nullable();
            $table->integer('payment_token_confirmed')->default(0);
            $table->string('payment_description')->nullable();
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
        Schema::dropIfExists('payment');
    }
}
