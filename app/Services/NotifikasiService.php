<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke satu pengguna.
     */
    public static function kirim(int $penggunaId, string $judul, string $pesan, string $tipe = null, int $referensiId = null, string $referensiTipe = null): Notifikasi
    {
        return Notifikasi::create([
            'pengguna_id'    => $penggunaId,
            'judul'          => $judul,
            'pesan'          => $pesan,
            'tipe'           => $tipe,
            'referensi_id'   => $referensiId,
            'referensi_tipe' => $referensiTipe,
            'dibaca'         => false,
        ]);
    }

    /**
     * Kirim notifikasi ke semua pengguna dengan role tertentu.
     */
    public static function kirimKeRole(string $role, string $judul, string $pesan, string $tipe = null, int $referensiId = null, string $referensiTipe = null): void
    {
        $users = User::where('role', $role)->where('status', 'aktif')->get();

        foreach ($users as $user) {
            static::kirim($user->id, $judul, $pesan, $tipe, $referensiId, $referensiTipe);
        }
    }

    /**
     * Kirim notifikasi ke semua Pengurus Wilayah.
     */
    public static function notifyPengurusWilayah(string $judul, string $pesan, string $tipe = null, int $referensiId = null): void
    {
        static::kirimKeRole('pengurus_wilayah', $judul, $pesan, $tipe, $referensiId, 'jadwal_itikaf');
    }

    /**
     * Kirim notifikasi ke semua Pengurus Inti.
     */
    public static function notifyPengurusInti(string $judul, string $pesan, string $tipe = null, int $referensiId = null): void
    {
        static::kirimKeRole('pengurus_inti', $judul, $pesan, $tipe, $referensiId, 'laporan_itikaf');
    }
}
