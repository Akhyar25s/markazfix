<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi - {{ $jadwal->nama_itikaf }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }
        
        /* Header */
        .header { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff; padding: 24px 30px; }
        .header-title { font-size: 20px; font-weight: 700; letter-spacing: -0.5px; }
        .header-subtitle { font-size: 12px; color: #f97316; margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .header-meta { margin-top: 12px; font-size: 10px; color: #94a3b8; }

        /* Info Grid */
        .info-section { padding: 16px 30px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 4px 16px 4px 0; width: 50%; vertical-align: top; }
        .info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; font-weight: 600; }
        .info-value { font-size: 11px; color: #1e293b; font-weight: 500; margin-top: 2px; }
        
        /* Stats */
        .stats-section { padding: 16px 30px; display: table; width: 100%; border-spacing: 8px 0; }
        .stat-box { display: table-cell; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; text-align: center; width: 25%; }
        .stat-number { font-size: 22px; font-weight: 700; }
        .stat-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; margin-top: 2px; }
        .stat-blue   { color: #3b82f6; }
        .stat-green  { color: #22c55e; }
        .stat-red    { color: #ef4444; }
        .stat-orange { color: #f97316; }

        /* Table */
        .table-section { padding: 0 30px 30px; }
        .section-title { font-size: 12px; font-weight: 700; color: #1e293b; padding: 16px 0 10px; border-bottom: 2px solid #f97316; margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 0; }
        thead tr { background: #1e293b; }
        thead th { padding: 9px 10px; text-align: left; color: #e2e8f0; font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 8px 10px; font-size: 10px; color: #374151; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }

        /* Footer */
        .footer { padding: 16px 30px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-subtitle">Sistem Informasi Manajemen Organisasi</div>
        <div class="header-title">Laporan Presensi I'tikaf</div>
        <div class="header-meta">Digenerate otomatis pada: {{ now()->format('d F Y, H:i:s') }} WIB</div>
    </div>

    {{-- Jadwal Info --}}
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Nama Kegiatan</div>
                    <div class="info-value">{{ $jadwal->nama_itikaf }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Lokasi</div>
                    <div class="info-value">{{ $jadwal->nama_lokasi ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Tanggal Pelaksanaan</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d F Y') }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ ucfirst($jadwal->status) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-section">
        <div class="stat-box" style="background:#eff6ff;">
            <div class="stat-number stat-blue">{{ $stats['total_peserta'] }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
        <div class="stat-box" style="background:#f0fdf4;">
            <div class="stat-number stat-green">{{ $stats['total_hadir'] }}</div>
            <div class="stat-label">Hadir</div>
        </div>
        <div class="stat-box" style="background:#fef2f2;">
            <div class="stat-number stat-red">{{ $stats['total_tidak_hadir'] }}</div>
            <div class="stat-label">Belum Hadir</div>
        </div>
        <div class="stat-box" style="background:#fff7ed;">
            <div class="stat-number stat-orange">{{ $stats['pct_kehadiran'] }}%</div>
            <div class="stat-label">Kehadiran</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-section">
        <div class="section-title">Rincian Data Kehadiran</div>
        <table>
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:22%">Nama Peserta</th>
                    <th style="width:22%">Email</th>
                    <th style="width:15%">Wilayah</th>
                    <th style="width:16%">Waktu Absen</th>
                    <th style="width:10%">Jarak</th>
                    <th style="width:11%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $i => $row)
                    <tr>
                        <td style="text-align:center; color:#94a3b8;">{{ $i + 1 }}</td>
                        <td style="font-weight:600;">{{ $row->pengguna_name }}</td>
                        <td style="color:#64748b; font-size:9px;">{{ $row->pengguna_email }}</td>
                        <td>{{ $row->wilayah_nama ?? '-' }}</td>
                        <td style="font-size:9px; white-space:nowrap;">{{ \Carbon\Carbon::parse($row->waktu_absen)->format('d M Y, H:i') }}</td>
                        <td style="text-align:center;">
                            @if($row->jarak_meter !== null)
                                <span style="color:{{ $row->status_gps == 'valid' ? '#166534' : '#991b1b' }}; font-weight:600;">{{ $row->jarak_meter }} m</span>
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($row->status_absen == 'berhasil')
                                <span class="badge badge-success">Hadir</span>
                            @else
                                <span class="badge badge-danger">Gagal</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px; color:#94a3b8;">Tidak ada data absensi untuk jadwal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh Sistem Informasi Manajemen Organisasi MARKAZ &bull; Dicetak: {{ now()->format('d F Y H:i') }}
    </div>

</body>
</html>
