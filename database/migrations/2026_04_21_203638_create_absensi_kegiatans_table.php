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
        Schema::create('absensi_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->unsignedBigInteger('jenis_kegiatan_id');
            $table->timestamp('waktu_kegiatan')->useCurrent();
            $table->enum('status_wajah', ['dikenali', 'tidak_dikenali'])->nullable();
            $table->enum('status_absen', ['berhasil', 'gagal']);
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jenis_kegiatan_id')->references('id')->on('jenis_kegiatans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_kegiatans');
    }
};
