<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionHandleV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_handle_v2', function (Blueprint $table) {
            $table->id('mission_handle_id');
            $table->bigInteger('real_mission_id');
            $table->bigInteger('mission_user');
            $table->integer('is_completed')->default(0);
            $table->timestamp('viewed_at')->default(null);
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
        Schema::dropIfExists('mission_handle_v2');
    }
}
