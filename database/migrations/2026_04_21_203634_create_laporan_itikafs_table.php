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
        Schema::create('laporan_itikafs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_itikaf_id');
            $table->unsignedBigInteger('amir_id');
            $table->string('nama_sesi', 150);
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->text('uraian_kegiatan');
            $table->json('peserta_hadir')->nullable();
            $table->enum('status', ['draft', 'menunggu_wilayah', 'dikembalikan_wilayah', 'menunggu_inti', 'dikembalikan_inti', 'disetujui'])->default('draft');
            $table->text('catatan_wilayah')->nullable();
            $table->text('catatan_inti')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
            
            $table->foreign('jadwal_itikaf_id')->references('id')->on('jadwal_itikafs')->onDelete('cascade');
            $table->foreign('amir_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_itikafs');
    }
};
