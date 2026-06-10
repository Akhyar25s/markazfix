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

        // Kelola Wilayah & Mahallah
        Route::resource('wilayah', WilayahController::class);
        Route::resource('mahallah', MahallahController::class);
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
    // LOGOUT (semua role)
    // ============================================================
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
