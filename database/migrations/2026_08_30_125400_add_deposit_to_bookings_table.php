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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'deposit')) {
                $table->integer('deposit')->default(0)->after('status_pembayaran');
            }
            if (!Schema::hasColumn('bookings', 'tagihan_susulan')) {
                $table->integer('tagihan_susulan')->default(0)->after('status_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['deposit', 'tagihan_susulan']);
        });
    }
};
