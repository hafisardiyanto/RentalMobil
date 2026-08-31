<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('seats')->default(5);
            $table->integer('luggage')->default(2);
            $table->text('facilities')->nullable(); // Store as JSON text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['description', 'seats', 'luggage', 'facilities']);
        });
    }
};
