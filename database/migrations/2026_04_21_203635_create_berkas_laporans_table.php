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
        Schema::create('berkas_laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laporan_itikaf_id');
            $table->enum('tipe_file', ['foto', 'dokumen']);
            $table->string('file_path', 255);
            $table->string('nama_file', 255);
            $table->integer('ukuran_bytes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('laporan_itikaf_id')->references('id')->on('laporan_itikafs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_laporans');
    }
};
