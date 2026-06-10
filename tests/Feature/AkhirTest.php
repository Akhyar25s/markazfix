<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AkhirTest extends TestCase
{
    // Kita gunakan db transaksi tanpa refresh full agar data dummy tetap ada
    
    public function test_dashboard_inti_dapat_diakses()
    {
        $user = User::where('role', 'pengurus_inti')->first();
        if (!$user) {
            $this->markTestSkipped('Tidak ada user pengurus inti.');
        }

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Data Anggota'); // Tombol export
    }

    public function test_dashboard_wilayah_dapat_diakses()
    {
        $user = User::where('role', 'pengurus_wilayah')->first();
        if (!$user) {
            $this->markTestSkipped('Tidak ada user pengurus wilayah.');
        }

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_dashboard_anggota_dapat_diakses()
    {
        $user = User::where('role', 'anggota')->first();
        if (!$user) {
            $this->markTestSkipped('Tidak ada user anggota.');
        }

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Progres Target Kegiatan'); 
    }

    public function test_export_anggota_pdf()
    {
        $user = User::where('role', 'pengurus_inti')->first();
        if (!$user) {
            $this->markTestSkipped('Tidak ada user pengurus inti.');
        }

        $response = $this->actingAs($user)->get('/export/anggota/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_export_anggota_excel()
    {
        $user = User::where('role', 'pengurus_inti')->first();
        if (!$user) {
            $this->markTestSkipped('Tidak ada user pengurus inti.');
        }

        $response = $this->actingAs($user)->get('/export/anggota/excel');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}

