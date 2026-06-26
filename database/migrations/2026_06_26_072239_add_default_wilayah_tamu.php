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
        $exists = \Illuminate\Support\Facades\DB::table('wilayahs')->where('nama_wilayah', 'Tamu')->exists();
        if (!$exists) {
            \Illuminate\Support\Facades\DB::table('wilayahs')->insert([
                'nama_wilayah' => 'Tamu',
                'deskripsi' => 'Wilayah Penampung Default untuk Tamu/Lainnya',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('wilayahs')->where('nama_wilayah', 'Tamu')->delete();
    }
};
