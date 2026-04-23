<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalItikafController;
use App\Http\Controllers\PesertaItikafController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\MahallahController;
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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rute Jadwal I'tikaf (Pengurus Inti)
    Route::get('/jadwal', [JadwalItikafController::class, 'index']);
    Route::get('/jadwal/create', [JadwalItikafController::class, 'create']);
    Route::post('/jadwal', [JadwalItikafController::class, 'store']);
    
    // Rute Peserta I'tikaf (Pengurus Wilayah)
    Route::get('/peserta', [PesertaItikafController::class, 'index']);
    Route::get('/peserta/{id}/daftar', [PesertaItikafController::class, 'create']);
    Route::post('/peserta/{id}/daftar', [PesertaItikafController::class, 'store']);
    
    // Rute Wilayah & Mahallah
    Route::resource('wilayah', WilayahController::class);
    Route::resource('mahallah', MahallahController::class);
    
    // Rute logout sederhana (biasanya POST, ini untuk simulasi)
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
