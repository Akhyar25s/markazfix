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
        Schema::create('absensi_itikafs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_itikaf_id');
            $table->unsignedBigInteger('pengguna_id');
            $table->timestamp('waktu_absen')->nullable();
            $table->decimal('latitude_aktual', 10, 8)->nullable();
            $table->decimal('longitude_aktual', 11, 8)->nullable();
            $table->integer('jarak_meter')->nullable();
            $table->enum('status_gps', ['valid', 'diluar_radius'])->nullable();
            $table->enum('status_wajah', ['dikenali', 'tidak_dikenali'])->nullable();
            $table->enum('status_absen', ['berhasil', 'gagal']);
            $table->string('keterangan_gagal', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('jadwal_itikaf_id')->references('id')->on('jadwal_itikafs')->onDelete('cascade');
            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_itikafs');
    }
};
