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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_booking')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('durasi')->default(1);
            $table->integer('harga_per_hari');
            $table->integer('subtotal');
            $table->integer('diskon')->default(0);
            $table->integer('biaya_tambahan')->default(0);
            $table->integer('total');
            $table->integer('deposit')->default(0);
            $table->string('status_booking')->default('Menunggu Konfirmasi');
            $table->string('status_pembayaran')->default('Belum Bayar');
            $table->text('catatan')->nullable();
            // Handover & Return Columns
            $table->integer('km_awal')->nullable();
            $table->string('bbm_awal')->nullable();
            $table->text('kondisi_awal')->nullable();
            $table->string('foto_awal')->nullable();
            $table->integer('km_akhir')->nullable();
            $table->string('bbm_akhir')->nullable();
            $table->text('kondisi_akhir')->nullable();
            $table->string('foto_akhir')->nullable();
            $table->datetime('waktu_pengembalian')->nullable();
            $table->integer('denda_terlambat')->default(0);
            $table->integer('biaya_kerusakan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
