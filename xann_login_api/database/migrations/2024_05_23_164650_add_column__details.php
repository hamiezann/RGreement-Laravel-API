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
    public function up(): void
    {
        Schema::table('house_details', function (Blueprint $table) {
            $table->boolean('available')->default(true); // Add the 'available' column with a default value of true
            $table->string('contract_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('house_details', function (Blueprint $table) {
            $table->dropColumn('available');
            $table->dropColumn('contract_status');
        });
    }
};
