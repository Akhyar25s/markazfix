<!DOCTYPE html>
<html>
<head>
    <title>Daftar Anggota</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Daftar Anggota MARKAZ</h2>
    <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Wilayah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggotas as $index => $anggota)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $anggota->name }}</td>
                <td>{{ $anggota->email }}</td>
                <td>{{ $anggota->wilayah->nama ?? '-' }}</td>
                <td>{{ ucfirst($anggota->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
