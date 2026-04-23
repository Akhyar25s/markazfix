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
        Schema::create('pendaftaran_wajahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->string('aws_face_id', 100);
            $table->string('aws_collection_id', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('terdaftar_pada')->useCurrent();
            $table->timestamps();
            
            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_wajahs');
    }
};
