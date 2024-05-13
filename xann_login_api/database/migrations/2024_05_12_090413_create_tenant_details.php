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
        Schema::create('tenant_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('tenant_status');
            $table->unsignedBigInteger('house_id');
            $table->string('sign_contract_status');
            
            $table->timestamps();

                        // Define foreign key constraints
                        $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
                        $table->foreign('house_id')->references('id')->on('house_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_details');
    }
};
