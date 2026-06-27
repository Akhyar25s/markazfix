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
        Schema::table('jadwal_itikafs', function (Blueprint $table) {
            $table->unsignedBigInteger('mahallah_id')->nullable()->after('status');
            $table->unsignedBigInteger('tempat_ibadah_id')->nullable()->after('mahallah_id');

            $table->foreign('mahallah_id')->references('id')->on('mahallahs')->onDelete('set null');
            $table->foreign('tempat_ibadah_id')->references('id')->on('tempat_ibadahs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_itikafs', function (Blueprint $table) {
            $table->dropForeign(['mahallah_id']);
            $table->dropForeign(['tempat_ibadah_id']);
            $table->dropColumn(['mahallah_id', 'tempat_ibadah_id']);
        });
    }
};
