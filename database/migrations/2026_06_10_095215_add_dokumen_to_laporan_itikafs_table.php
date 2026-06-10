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
        Schema::table('laporan_itikafs', function (Blueprint $table) {
            $table->json('dokumen_pendukung')->nullable()->after('peserta_hadir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_itikafs', function (Blueprint $table) {
            $table->dropColumn('dokumen_pendukung');
        });
    }
};
