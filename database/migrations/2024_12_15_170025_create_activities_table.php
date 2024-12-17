<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('sport_center_id');
        $table->string('sportType');
        $table->date('date');
        $table->string('startTime');
        $table->string('endTime');
        $table->integer('player_quantity');
        $table->decimal('price_per_pax', 10, 2);
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('sport_center_id')->references('id')->on('sport_centers')->onDelete('cascade');
    });

    Schema::create('activity_user', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('activity_id');
        $table->unsignedBigInteger('user_id');
        $table->integer('player_quantity');
        $table->timestamps();

        $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
