<?php
// We output a raw HTML table with inline styles. Excel will interpret this perfectly.
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Excel</title>
    <style>
        table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px; }
        th, td { border: 1px solid #000000; padding: 5px; text-align: center; vertical-align: middle; }
        .title { font-size: 14px; font-weight: bold; border: none; text-align: center; }
        .subtitle { font-size: 12px; font-weight: bold; border: none; text-align: center; }
        .header-green { background-color: #a9d08e; font-weight: bold; } /* Matching the green from image */
        .header-blue { background-color: #9bc2e6; font-weight: bold; }  /* Matching the blue from image */
        .header-yellow { background-color: #ffd966; font-weight: bold; } /* Matching the yellow from image */
        .header-red { background-color: #ff0000; color: white; font-weight: bold; }
        .row-alt { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <table>
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
</body>
</html>
