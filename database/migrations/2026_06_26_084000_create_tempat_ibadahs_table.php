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
        Schema::create('tempat_ibadahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahallah_id');
            $table->string('nama', 150);
            $table->enum('jenis', ['masjid', 'langgar', 'mushola', 'lainnya']);
            $table->string('foto')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_meter')->default(100);
            $table->timestamps();

            $table->foreign('mahallah_id')->references('id')->on('mahallahs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_ibadahs');
    }
};
