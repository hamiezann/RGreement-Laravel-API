<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('house_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Use unsignedBigInteger to match the primary key datatype
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('latitude');
            $table->unsignedBigInteger('longitude');
            $table->string('uni_identifier');
            $table->string('prefered_occupants');
            $table->string('type_of_house');
            $table->text('description');
            $table->float('rent_fee');
            $table->tinyInteger('number_of_rooms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_details');
    }
};
