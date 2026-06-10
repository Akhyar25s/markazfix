@extends('layouts.app')

@section('title', 'Laporan - ' . $jadwal->nama_itikaf)

@section('content')
<div class="space-y-6 animate-fade-in pb-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('laporan.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground line-clamp-1">{{ $jadwal->nama_itikaf }}</h1>
                <p class="text-muted-foreground text-sm mt-0.5">{{ $jadwal->nama_lokasi ?? 'Lokasi tidak diketahui' }} · {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M') }} — {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Export Buttons --}}
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('laporan.export-csv', $jadwal->id) }}" target="_blank"
               class="flex items-center gap-2 bg-green-600/90 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all hover:shadow-lg hover:shadow-green-600/30">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
            <a href="{{ route('laporan.export-pdf', $jadwal->id) }}" 
               class="flex items-center gap-2 bg-red-600/90 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all hover:shadow-lg hover:shadow-red-600/30">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <x-card class="bg-card/80 backdrop-blur border-primary/10 p-4 text-center">
            <div class="text-3xl font-extrabold text-foreground">{{ $stats['total_peserta'] }}</div>
            <div class="text-xs text-muted-foreground mt-1 uppercase tracking-wider">Total Peserta</div>
        </x-card>
        <x-card class="bg-card/80 backdrop-blur border-green-500/20 p-4 text-center">
            <div class="text-3xl font-extrabold text-green-500">{{ $stats['total_hadir'] }}</div>
            <div class="text-xs text-muted-foreground mt-1 uppercase tracking-wider">Hadir</div>
        </x-card>
        <x-card class="bg-card/80 backdrop-blur border-red-500/20 p-4 text-center">
            <div class="text-3xl font-extrabold text-red-500">{{ $stats['total_tidak_hadir'] }}</div>
            <div class="text-xs text-muted-foreground mt-1 uppercase tracking-wider">Belum Hadir</div>
        </x-card>
        <x-card class="bg-card/80 backdrop-blur border-primary/20 p-4 text-center relative overflow-hidden">
            <div class="text-3xl font-extrabold text-primary">{{ $stats['pct_kehadiran'] }}%</div>
            <div class="text-xs text-muted-foreground mt-1 uppercase tracking-wider">Kehadiran</div>
            {{-- Progress bar --}}
            <div class="absolute bottom-0 left-0 h-1 bg-primary/30 w-full">
                <div class="h-full bg-primary transition-all" style="width: {{ $stats['pct_kehadiran'] }}%"></div>
            </div>
        </x-card>
    </div>

    {{-- Attendance Table --}}
    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center mb-4 px-1">
            <h2 class="text-lg font-semibold text-foreground">Rincian Data Kehadiran</h2>
            <span class="text-xs text-muted-foreground bg-muted px-2 py-1 rounded-md">{{ count($absensi) }} record</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border/60">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">#</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Nama</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider hidden md:table-cell">Wilayah</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider hidden lg:table-cell">Waktu Absen</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider hidden lg:table-cell">Jarak</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/30">
                    @forelse($absensi as $i => $row)
                        <tr class="hover:bg-muted/20 transition-colors">
                            <td class="py-3 px-4 text-muted-foreground">{{ $i + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-foreground">{{ $row->pengguna_name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $row->pengguna_email }}</div>
                            </td>
                            <td class="py-3 px-4 text-muted-foreground hidden md:table-cell">{{ $row->wilayah_nama ?? '-' }}</td>
                            <td class="py-3 px-4 text-muted-foreground text-xs hidden lg:table-cell whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($row->waktu_absen)->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3 px-4 text-center hidden lg:table-cell">
                                @if($row->jarak_meter !== null)
                                    <span class="text-xs font-medium {{ $row->status_gps == 'valid' ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $row->jarak_meter }} m
                                    </span>
                                @else
                                    <span class="text-muted-foreground text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($row->status_absen == 'berhasil')
                                    <x-badge class="bg-green-500/10 text-green-500 border-green-500/20">Hadir</x-badge>
                                @else
                                    <x-badge class="bg-red-500/10 text-red-500 border-red-500/20">Gagal</x-badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3 text-muted-foreground">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 opacity-40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <div>
                                        <p class="font-medium">Belum ada data absensi</p>
                                        <p class="text-xs mt-1">Data akan muncul setelah peserta melakukan absensi via Face Recognition.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
