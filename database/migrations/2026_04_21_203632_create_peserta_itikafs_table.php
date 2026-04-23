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
        Schema::create('peserta_itikafs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_itikaf_id');
            $table->unsignedBigInteger('pengguna_id');
            $table->boolean('adalah_amir')->default(false);
            $table->unsignedBigInteger('dipilih_oleh')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('jadwal_itikaf_id')->references('id')->on('jadwal_itikafs')->onDelete('cascade');
            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dipilih_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_itikafs');
    }
};
