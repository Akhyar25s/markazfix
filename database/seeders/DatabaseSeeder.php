<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Wilayah
        $wilayah1 = \App\Models\Wilayah::create([
            'nama_wilayah' => 'Halaqah Wilayah 1',
            'deskripsi' => 'Wilayah percontohan',
        ]);

        // 2. Buat Mahallah
        $mahallah1 = \App\Models\Mahallah::create([
            'wilayah_id' => $wilayah1->id,
            'nama_mahallah' => 'Masjid Al-Falah',
            'alamat' => 'Jl. Kebayoran Baru No. 1',
        ]);

        $mahallah2 = \App\Models\Mahallah::create([
            'wilayah_id' => $wilayah1->id,
            'nama_mahallah' => 'Masjid An-Nur',
            'alamat' => 'Jl. Kebayoran Baru No. 2',
        ]);

        // 3. Buat Akun Pengurus Inti
        User::create([
            'name' => 'Bapak Pengurus Inti',
            'email' => 'inti@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08111111111',
            'role' => 'pengurus_inti',
            'status' => 'aktif',
        ]);

        // 4. Buat Akun Pengurus Wilayah (Ditempatkan di Wilayah 1)
        User::create([
            'name' => 'Bapak Pengurus Wilayah',
            'email' => 'wilayah@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08222222222',
            'role' => 'pengurus_wilayah',
            'wilayah_id' => $wilayah1->id,
            'status' => 'aktif',
        ]);

        // 5. Buat Akun Anggota
        User::create([
            'name' => 'Umar Anggota',
            'email' => 'umar@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08333333333',
            'role' => 'anggota',
            'wilayah_id' => $wilayah1->id,
            'mahallah_id' => $mahallah1->id,
            'status' => 'aktif',
        ]);
    }
}
