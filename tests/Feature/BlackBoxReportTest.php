<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\File;

class BlackBoxReportTest extends TestCase
{
    private $reportData = [];

    protected function setUp(): void
    {
        parent::setUp();
        // Assume db is already seeded
    }

    private function recordTest($fungsi, $input, $expected, $actual, $status, $bug = '-')
    {
        $this->reportData[] = [
            'fungsi' => $fungsi,
            'input' => json_encode($input),
            'expected' => $expected,
            'actual' => $actual,
            'status' => $status,
            'bug' => $bug
        ];
    }

    public function test_run_all_black_box_tests()
    {
        // 1. Auth Login - Valid
        $response = $this->post('/login', [
            'email' => 'inti@markaz.com',
            'password' => 'password123'
        ]);
        $status = $response->status() == 302 && session()->has('auth') || auth()->check() ? 'PASS' : 'FAIL';
        $this->recordTest(
            'Autentikasi (Login)', 
            ['email' => 'inti@markaz.com', 'password' => 'password123'], 
            'Redirect ke dashboard / Berhasil login', 
            'Status code: ' . $response->status() . (auth()->check() ? ' (Logged In)' : ''), 
            auth()->check() ? 'PASS' : 'FAIL'
        );

        auth()->logout();

        // 2. Auth Login - Invalid
        $response = $this->post('/login', [
            'email' => 'inti@markaz.com',
            'password' => 'salah123'
        ]);
        $this->recordTest(
            'Autentikasi (Login Salah)', 
            ['email' => 'inti@markaz.com', 'password' => 'salah123'], 
            'Gagal login, ada error message', 
            'Status code: ' . $response->status() . (session('errors') ? ' (Has Errors)' : ''), 
            session('errors') ? 'PASS' : 'FAIL'
        );

        // 3. Jadwal Itikaf - Pengurus Inti (Valid)
        $intiUser = User::where('role', 'pengurus_inti')->first();
        $response = $this->actingAs($intiUser)->post('/jadwal', [
            'nama_jadwal' => 'I\'tikaf Ramadhan 2026',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-07-10',
            'deskripsi' => 'Itikaf 10 hari terakhir'
        ]);
        $this->recordTest(
            'Buat Jadwal (Valid)', 
            ['nama_jadwal' => 'I\'tikaf Ramadhan 2026', 'tanggal' => '01-10 Jul 2026'], 
            'Jadwal tersimpan, redirect dengan sukses', 
            'Status code: ' . $response->status(), 
            $response->status() == 302 ? 'PASS' : 'FAIL'
        );

        // 4. Jadwal Itikaf - Invalid Date (End < Start)
        $response = $this->actingAs($intiUser)->post('/jadwal', [
            'nama_jadwal' => 'I\'tikaf Invalid',
            'tanggal_mulai' => '2026-08-10',
            'tanggal_selesai' => '2026-08-01',
            'deskripsi' => 'Tanggal mundur'
        ]);
        $isError = session('errors') ? true : false;
        $this->recordTest(
            'Buat Jadwal (Invalid Date)', 
            ['mulai' => '2026-08-10', 'selesai' => '2026-08-01'], 
            'Validasi gagal, harus menampilkan error', 
            'Status code: ' . $response->status() . ($isError ? ' (Validation Error)' : ' (No Error)'), 
            $isError ? 'PASS' : 'FAIL',
            $isError ? '-' : 'Sistem menerima tanggal selesai yang lebih kecil dari tanggal mulai'
        );

        // 5. Notifikasi - Tandai Dibaca
        // First create a notification
        $notif = \App\Models\Notifikasi::create([
            'pengguna_id' => $intiUser->id,
            'judul' => 'Test Notif',
            'pesan' => 'Isi notif test',
            'dibaca' => false
        ]);
        $response = $this->actingAs($intiUser)->get('/notifikasi/' . $notif->id . '/tandai');
        $notif->refresh();
        $this->recordTest(
            'Notifikasi (Tandai Dibaca)', 
            ['notifikasi_id' => $notif->id], 
            'Notifikasi berubah menjadi dibaca = true', 
            'Status code: ' . $response->status() . ', dibaca: ' . $notif->dibaca, 
            $notif->dibaca ? 'PASS' : 'FAIL'
        );

        $this->generateReport();
    }

    private function generateReport()
    {
        $markdown = "# Laporan Pengujian Black Box Testing\n\n";
        $markdown .= "Pengujian ini dijalankan secara otomatis dengan melakukan simulasi HTTP request untuk menguji *input* dan *output* (Black Box Testing).\n\n";
        
        $markdown .= "| Fungsi yang Diuji | Input | Expected Output | Actual Output | Status | Bug / Catatan |\n";
        $markdown .= "|-------------------|-------|-----------------|---------------|--------|---------------|\n";
        
        foreach ($this->reportData as $row) {
            $statusStr = $row['status'] == 'PASS' ? '✅ PASS' : '❌ FAIL';
            $markdown .= "| {$row['fungsi']} | `{$row['input']}` | {$row['expected']} | {$row['actual']} | {$statusStr} | {$row['bug']} |\n";
        }

        $path = 'C:\Users\ASUS\.gemini\antigravity-ide\brain\f48c6741-f45a-4516-87a3-6f22eb0dec8e\black_box_testing_report.md';
        File::put($path, $markdown);
    }
}
