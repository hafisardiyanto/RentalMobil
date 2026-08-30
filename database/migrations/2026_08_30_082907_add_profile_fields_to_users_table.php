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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 50)->nullable()->after('phone');
            $table->text('address')->nullable()->after('nik');
            $table->string('sim_photo')->nullable()->after('address');
            $table->string('ktp_photo')->nullable()->after('sim_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'address', 'sim_photo', 'ktp_photo']);
        });
    }
};
