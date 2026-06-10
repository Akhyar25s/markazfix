<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JadwalItikaf;
use App\Models\LaporanItikaf;
use App\Models\AbsensiKegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    /**
     * Export Daftar Anggota
     */
    public function exportAnggota(Request $request, $format)
    {
        $query = User::with('wilayah')->where('role', 'anggota');
        
        if (Auth::user()->role === 'pengurus_wilayah') {
            $query->where('wilayah_id', Auth::user()->wilayah_id);
        }

        $anggotas = $query->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('export.anggota-pdf', compact('anggotas'));
            return $pdf->download('daftar_anggota.pdf');
        } elseif ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Wilayah');
            $sheet->setCellValue('E1', 'Status');

            $row = 2;
            foreach ($anggotas as $index => $a) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $a->name);
                $sheet->setCellValue('C' . $row, $a->email);
                $sheet->setCellValue('D' . $row, $a->wilayah->nama ?? '-');
                $sheet->setCellValue('E' . $row, ucfirst($a->status));
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="daftar_anggota.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
        }
        
        abort(404);
    }

    /**
     * Export Detail Laporan Sesi
     */
    public function exportLaporanSesi($id, $format)
    {
        $laporan = LaporanItikaf::with(['jadwal', 'amir'])->findOrFail($id);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('export.laporan-sesi-pdf', compact('laporan'));
            return $pdf->download('laporan_sesi_'.$laporan->id.'.pdf');
        } elseif ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setCellValue('A1', 'Detail Laporan Sesi I\'tikaf');
            $sheet->setCellValue('A3', 'Jadwal');
            $sheet->setCellValue('B3', $laporan->jadwal->nama_itikaf ?? '-');
            $sheet->setCellValue('A4', 'Nama Sesi');
            $sheet->setCellValue('B4', $laporan->nama_sesi);
            $sheet->setCellValue('A5', 'Amir');
            $sheet->setCellValue('B5', $laporan->amir->name ?? '-');
            $sheet->setCellValue('A6', 'Waktu');
            $sheet->setCellValue('B6', $laporan->waktu_mulai . ' s/d ' . $laporan->waktu_selesai);
            
            $sheet->setCellValue('A8', 'Uraian Kegiatan');
            $sheet->setCellValue('A9', $laporan->uraian_kegiatan);

            $writer = new Xlsx($spreadsheet);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="laporan_sesi_'.$laporan->id.'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
        }

        abort(404);
    }
}

