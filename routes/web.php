<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalItikafController;
use App\Http\Controllers\PesertaItikafController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\MahallahController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\FaceRecognitionController;
use App\Http\Controllers\AmirLaporanController;
use App\Http\Controllers\PersetujuanLaporanController;
use App\Http\Controllers\JenisKegiatanController;
use App\Http\Controllers\TargetKegiatanController;
use App\Http\Controllers\AbsensiKegiatanController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth'])->group(function () {

    // Dashboard (semua role)
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ============================================================
    // PENGURUS INTI ONLY
    // ============================================================
    Route::middleware('role:pengurus_inti')->group(function () {
        // Kelola Jadwal I'tikaf
        Route::get('/jadwal', [JadwalItikafController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/create', [JadwalItikafController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal', [JadwalItikafController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{id}/peserta', [JadwalItikafController::class, 'peserta'])->name('jadwal.peserta');
        Route::post('/jadwal/{id}/peserta/{peserta_id}/jadikan-amir', [JadwalItikafController::class, 'jadikanAmir'])->name('jadwal.jadikan-amir');

        // Kelola Wilayah & Mahallah
        Route::resource('wilayah', WilayahController::class);
        Route::resource('mahallah', MahallahController::class);

        // Modul M4: Master Jenis Kegiatan & Target Kegiatan
        Route::resource('jenis-kegiatan', JenisKegiatanController::class)->except(['show']);
        Route::resource('target-kegiatan', TargetKegiatanController::class)->except(['show']);
    });

    // ============================================================
    // PENGURUS WILAYAH ONLY
    // ============================================================
    Route::middleware('role:pengurus_wilayah')->group(function () {
        // Kelola Peserta I'tikaf
        Route::get('/peserta', [PesertaItikafController::class, 'index'])->name('peserta.index');
        Route::get('/peserta/{id}/daftar', [PesertaItikafController::class, 'create'])->name('peserta.create');
        Route::post('/peserta/{id}/daftar', [PesertaItikafController::class, 'store'])->name('peserta.store');

        // Pendaftaran Wajah (untuk mendaftarkan anggota wilayahnya)
        Route::get('/face/enroll', [FaceRecognitionController::class, 'showEnrollmentForm'])->name('face.enroll');
        Route::post('/face/enroll', [FaceRecognitionController::class, 'enroll']);
    });

    // ============================================================
    // AMIR I'TIKAF — Semua user bisa akses, controller memvalidasi
    // apakah user tersebut benar-benar Amir pada jadwal yang dimaksud
    // ============================================================
    Route::prefix('amir')->name('amir.')->group(function () {
        Route::get('/laporan', [AmirLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{jadwal_id}/sesi', [AmirLaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/{jadwal_id}/buat', [AmirLaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan/{jadwal_id}/buat', [AmirLaporanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{id}/edit', [AmirLaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/laporan/{id}/edit', [AmirLaporanController::class, 'update'])->name('laporan.update');
        Route::post('/laporan/{id}/kirim', [AmirLaporanController::class, 'kirim'])->name('laporan.kirim');
        Route::post('/laporan/{id}/hapus-dokumen', [AmirLaporanController::class, 'hapusDokumen'])->name('laporan.hapus-dokumen');
    });

    // ============================================================
    // PERSETUJUAN LAPORAN — Pengurus Inti & Pengurus Wilayah
    // ============================================================
    Route::middleware('role:pengurus_inti,pengurus_wilayah')->prefix('persetujuan-laporan')->name('persetujuan.')->group(function () {
        Route::get('/', [PersetujuanLaporanController::class, 'index'])->name('index');
        Route::get('/{id}', [PersetujuanLaporanController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [PersetujuanLaporanController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PersetujuanLaporanController::class, 'reject'])->name('reject');
    });

    // ============================================================
    // PENGURUS INTI & PENGURUS WILAYAH (keduanya bisa)
    // ============================================================
    Route::middleware('role:pengurus_inti,pengurus_wilayah')->group(function () {
        // Laporan Presensi (Sudah di-scope di Controller)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/{id}/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/laporan/{id}/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.export-csv');

        // API peta (dipakai dashboard)
        Route::get('/api/mahallah-map', [MahallahController::class, 'getMapData'])->name('mahallah.map');

        // Absensi Face Recognition
        Route::get('/face/verify', [FaceRecognitionController::class, 'showVerificationForm'])->name('face.verify');
        Route::post('/face/verify', [FaceRecognitionController::class, 'verify']);
    });

    // ============================================================
    // MODUL M4: ABSENSI KEGIATAN INDIVIDUAL — semua role login bisa akses
    // ============================================================
    Route::prefix('kegiatan')->name('absensi-kegiatan.')->group(function () {
        Route::get('/', [AbsensiKegiatanController::class, 'index'])->name('index');
        Route::get('/rekam', [AbsensiKegiatanController::class, 'create'])->name('create');
        Route::post('/rekam', [AbsensiKegiatanController::class, 'store'])->name('store');
    });

    // ============================================================
    // MODUL M5: NOTIFIKASI IN-APP — semua role login bisa akses
    // ============================================================
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('index');
        Route::post('/tandai-semua', [\App\Http\Controllers\NotifikasiController::class, 'tandaiSemuaDibaca'])->name('tandai-semua');
        Route::get('/{id}/tandai', [\App\Http\Controllers\NotifikasiController::class, 'tandaiDibaca'])->name('tandai');
        Route::delete('/{id}', [\App\Http\Controllers\NotifikasiController::class, 'hapus'])->name('hapus');
        Route::get('/api/unread-count', [\App\Http\Controllers\NotifikasiController::class, 'jumlahBelumDibaca']);
    });

    // ============================================================
    // LOGOUT (semua role)
    // ============================================================
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
