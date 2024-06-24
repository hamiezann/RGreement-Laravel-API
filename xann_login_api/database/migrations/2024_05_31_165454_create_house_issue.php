<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('house_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('landlord_id');
            $table->foreign('landlord_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('renter_id');
            $table->foreign('renter_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('house_id');
            $table->foreign('house_id')->references('id')->on('house_details')->onDelete('cascade');
            $table->text('description');
            $table->string('image')->nullable();
            $table->decimal('amount_requested', 15, 2)->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('issue_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_issues');
    }
};
