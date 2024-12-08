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
        Schema::create('equipment_rentals', function (Blueprint $table) {
            $table->id('rentalID');
            $table->date('date');
            $table->time('startTime');
            $table->time('endTime');
            $table->integer('quantity_rented');
            $table->boolean('deposit_returned')->default(false);
            $table->string('rentalStatus')->default('Pending'); // e.g., Pending, Approved, Completed
            $table->timestamps();
    
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('equipmentID');
            $table->unsignedBigInteger('sport_center_id');
            
            $table->foreign('userID')->references('id')->on('users');
            $table->foreign('equipmentID')->references('equipmentID')->on('equipment');
            $table->foreign('sport_center_id')->references('id')->on('sport_centers');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_rentals');
    }
};
