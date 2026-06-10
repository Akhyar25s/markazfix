<!DOCTYPE html>
<html>
<head>
    <title>Detail Laporan Sesi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .info-table .label { font-weight: bold; width: 30%; }
        .content { border: 1px solid #ddd; padding: 15px; min-height: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Sesi I'tikaf</h2>
        <p>Jadwal: {{ $laporan->jadwal->nama_itikaf ?? '-' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Sesi</td>
            <td>: {{ $laporan->nama_sesi }}</td>
        </tr>
        <tr>
            <td class="label">Amir</td>
            <td>: {{ $laporan->amir->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Waktu</td>
            <td>: {{ $laporan->waktu_mulai }} s/d {{ $laporan->waktu_selesai }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: {{ ucfirst($laporan->status) }}</td>
        </tr>
    </table>

    <h3>Uraian Kegiatan:</h3>
    <div class="content">
        {!! nl2br(e($laporan->uraian_kegiatan)) !!}
    </div>
</body>
</html>
