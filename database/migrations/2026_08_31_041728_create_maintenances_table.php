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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Servis Rutin, Ganti Ban, Pajak, dll
            $table->text('description')->nullable();
            $table->date('service_date');
            $table->integer('cost')->default(0);
            $table->integer('last_km')->nullable();
            $table->integer('next_km')->nullable();
            $table->string('status')->default('Selesai'); // Selesai, Menunggu, Dalam Proses
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
