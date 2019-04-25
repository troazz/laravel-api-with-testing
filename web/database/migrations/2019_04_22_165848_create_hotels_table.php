<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->text('address');
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->enum('commission_type', ['percentage', 'exact']);
            $table->double('commission_amount');
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->enum('symbol_location', ['suffix', 'prefix']);
            $table->string('symbol', 5);
            $table->char('code', 3);
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->text('description')->nullable();
            $table->char('access_code', 7);
            $table->tinyInteger('max_occupancy');
            $table->double('net_rate');
            $table->unsignedInteger('currency_id');
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('amenities', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->text('description')->nullable();
        });

        Schema::create('amenity_room', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id');
            $table->unsignedInteger('amenity_id');
            $table->index(['room_id', 'amenity_id']);
            $table->foreign('room_id')->references('id')->on('rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amenity_room');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('hotels');
    }
}
