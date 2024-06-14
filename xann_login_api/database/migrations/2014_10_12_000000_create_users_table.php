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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->text('biography')->nullable();
            $table->string('career')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('verified_member')->default(false);
            $table->string('profile_pic')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('income', 15, 2)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
