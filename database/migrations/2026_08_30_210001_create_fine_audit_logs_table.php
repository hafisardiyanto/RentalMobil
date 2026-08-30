<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fine_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_fine_id')->nullable()->constrained('booking_fines')->onDelete('cascade');
            // Jika hapus booking_fine, kita tetep mau log nya ada, tapi ngga bisa constrained cascade dong?
            // User bilang "Jadi kalo dihapus log nya tetap ada". Maka table hapusnya pakai string identifier atau hapus log sekalian,
            // Lebih baik foreign null on delete set null
            // Tapi karena booking_id lebih utama:
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // Added, Modified, Deleted
            $table->text('details'); // "Menambahkan kerusakan Rp500.000" dll
            $table->integer('old_amount')->nullable();
            $table->integer('new_amount')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fine_audit_logs');
    }
};
