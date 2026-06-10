<?php
// Tampilan Preview Excel di Web
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview Laporan Excel</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 40px 20px; }
        .preview-container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 12px; text-align: center; vertical-align: middle; }
        .title { font-size: 16px; font-weight: bold; border: none; text-align: center; padding: 10px 0; }
        .subtitle { font-size: 14px; font-weight: bold; border: none; text-align: center; color: #4b5563; }
        .header-green { background-color: #a9d08e; font-weight: bold; color: #1f2937; } 
        .header-blue { background-color: #9bc2e6; font-weight: bold; color: #1f2937; }  
        .header-yellow { background-color: #ffd966; font-weight: bold; color: #1f2937; } 
        .header-red { background-color: #ef4444; color: white; font-weight: bold; }
        .row-alt { background-color: #f9fafb; }
        
        .action-bar { max-width: 1000px; margin: 0 auto 20px auto; display: flex; justify-content: space-between; align-items: center; }
        .btn-download { background-color: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; display: flex; items-center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); text-decoration: none;}
        .btn-download:hover { background-color: #059669; transform: translateY(-2px); }
        .btn-back { background-color: #6b7280; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-back:hover { background-color: #4b5563; }
        
        /* Hide action bar when downloading/printing */
        @media print { .action-bar { display: none; } body { padding: 0; background: white; } .preview-container { box-shadow: none; padding: 0; } }
    </style>
</head>
<body>
    <div class="action-bar" id="actionBar">
        <a href="{{ route('laporan.show', $jadwal->id) }}" class="btn-back">← Kembali</a>
        <button onclick="downloadExcel()" class="btn-download">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download file .xls
        </button>
    </div>

    <div class="preview-container" id="tableContainer">
        <table id="laporanTable">
        <tr>
            <td colspan="7" class="title">LAPORAN KEHADIRAN I'TIKAF MINGGUAN</td>
        </tr>
        <tr>
            <td colspan="7" class="subtitle">TAHUN {{ now()->format('Y') }}</td>
        </tr>
        <tr>
            <td colspan="7" style="border: none;"></td>
        </tr>
        
        <!-- Header Row 1 -->
        <tr>
            <th rowspan="2" class="header-green" style="width: 50px;">No.</th>
            <th rowspan="2" class="header-green" style="width: 200px;">NAMA PESERTA</th>
            <th rowspan="2" class="header-green" style="width: 150px;">WILAYAH</th>
            <th colspan="2" class="header-blue">DETAIL WAKTU & LOKASI</th>
            <th colspan="2" class="header-yellow">STATUS VERIFIKASI</th>
        </tr>
        <!-- Header Row 2 -->
        <tr>
            <th class="header-blue">WAKTU ABSEN</th>
            <th class="header-blue">JARAK (M)</th>
            <th class="header-yellow">STATUS WAJAH</th>
            <th class="header-yellow">HASIL AKHIR</th>
        </tr>

        <!-- Data Rows -->
        @forelse($absensi as $i => $row)
            <tr class="{{ $i % 2 == 0 ? '' : 'row-alt' }}">
                <td>{{ $i + 1 }}</td>
                <td style="text-align: left;">{{ $row->pengguna_name }}</td>
                <td style="text-align: left;">{{ $row->wilayah_nama ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($row->waktu_absen)->format('d/m/Y H:i') }}</td>
                <td>{{ $row->jarak_meter ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $row->status_wajah ?? '-')) }}</td>
                <td class="{{ $row->status_absen == 'berhasil' ? 'header-green' : 'header-red' }}">
                    {{ strtoupper($row->status_absen) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Belum ada data absensi</td>
            </tr>
        @endforelse
        
        <!-- Footer / Summary -->
        <tr>
            <th colspan="3" class="header-green" style="text-align: right;">TOTAL HADIR</th>
            <th colspan="4" class="header-green" style="text-align: left;">{{ $stats['total_hadir'] }} PESERTA</th>
        </tr>
    </table>
    </div>

    <script>
        function downloadExcel() {
            var tableHTML = document.getElementById("laporanTable").outerHTML;
            var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            @verbatim
            html += '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Laporan</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
            @endverbatim
            html += '<body>' + tableHTML + '</body></html>';

            var blob = new Blob(['\ufeff', html], {
                type: 'application/vnd.ms-excel'
            });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = "Laporan_Absensi_Excel_{{ \Illuminate\Support\Str::slug($jadwal->nama_itikaf, '_') }}.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
</body>
</html>
