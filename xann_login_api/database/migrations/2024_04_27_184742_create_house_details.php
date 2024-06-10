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
            // $table->unsignedBigInteger('latitude');
            // $table->unsignedBigInteger('longitude');
            $table->string('rent_address');
            $table->decimal('latitude', 10, 8); // Precision of 10 and scale of 8
            $table->decimal('longitude', 11, 8); // Precision of 11 and scale of 8
            $table->string('uni_identifier');
            $table->string('prefered_occupants');
            $table->string('type_of_house');
            $table->text('description');
            $table->float('rent_fee');
            $table->tinyInteger('number_of_rooms');
            $table->string('amenities');
            $table->string('num_bedrooms');
            $table->string('num_toilets');

            $table->timestamps();
        });

        // Schema::create('images', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('house_detail_id');
        //     $table->foreign('house_detail_id')->references('id')->on('house_details')->onDelete('cascade');
        //     $table->string('path');
        //     $table->timestamps();
        // });

    
            Schema::create('images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('imageable_id');
                $table->string('imageable_type');
                $table->string('path');
                $table->timestamps();
            });
        
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
        Schema::dropIfExists('house_details');
    }
};
