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
        Schema::create('target_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_kegiatan_id');
            $table->integer('jumlah_target');
            $table->enum('periode', ['bulanan', 'tahunan']);
            $table->year('tahun');
            $table->tinyInteger('bulan')->nullable();
            $table->unsignedBigInteger('ditetapkan_oleh');
            $table->timestamps();
            
            $table->foreign('jenis_kegiatan_id')->references('id')->on('jenis_kegiatans')->onDelete('cascade');
            $table->foreign('ditetapkan_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_kegiatans');
    }
};
