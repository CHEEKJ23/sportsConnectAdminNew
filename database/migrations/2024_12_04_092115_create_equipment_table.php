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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id('equipmentID');
            $table->string('name');
            $table->text('description');
            $table->decimal('price_per_hour', 8, 2);
            $table->integer('quantity_available');
            $table->string('condition')->default('Good');
            $table->decimal('deposit_amount', 8, 2);
            $table->string('image_path')->nullable(); 
            $table->unsignedBigInteger('sport_center_id');
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
        Schema::dropIfExists('equipment');
    }
};
