<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('avatar');
            $table->integer('gender')->default(1);
            $table->string('ref_code',50);
            $table->string('phone_number')->nullable();
            $table->integer('is_banned')->default(0);
            $table->rememberToken();
            $table->decimal('balance_cache')->default(0.00);
            $table->integer('xp_cache')->default(0);
            $table->integer('ref_cache')->default(0);
            $table->string('ip')->nullable();
            $table->timestamp('questions_completed_at')->nullable();
            $table->integer('is_verified')->default(0);
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
        Schema::dropIfExists('users');
    }
}
