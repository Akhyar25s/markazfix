<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_wajahs', function (Blueprint $table) {
            // Simpan 128 angka face descriptor sebagai JSON
            $table->text('face_descriptor')->nullable()->after('aws_collection_id');
            // Buat kolom aws lama menjadi nullable agar tidak wajib diisi
            $table->string('aws_face_id', 100)->nullable()->change();
            $table->string('aws_collection_id', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_wajahs', function (Blueprint $table) {
            $table->dropColumn('face_descriptor');
        });
    }
};
