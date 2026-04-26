@extends('layouts.app')

@section('title', 'Laporan Presensi')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Laporan Presensi</h1>
            <p class="text-muted-foreground mt-1 text-sm">Pilih jadwal I'tikaf untuk melihat dan mengekspor laporan kehadiran.</p>
        </div>
    </div>

    @if(session('error'))
        <x-alert type="danger" message="{{ session('error') }}" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jadwals as $jadwal)
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg hover:shadow-primary/20 hover:-translate-y-1 transition-all flex flex-col group relative overflow-hidden">
                {{-- Status line --}}
                <div class="absolute top-0 left-0 w-full h-1
                    {{ $jadwal->status == 'berlangsung' ? 'bg-blue-500' : ($jadwal->status == 'dijadwalkan' ? 'bg-amber-500' : ($jadwal->status == 'dibatalkan' ? 'bg-red-500' : 'bg-green-500')) }}">
                </div>

                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                            {{ $jadwal->nama_itikaf }}
                        </h3>
                        @if($jadwal->status == 'berlangsung')
                            <x-badge class="bg-blue-500/10 text-blue-500 border-blue-500/20 text-[10px] uppercase tracking-wider animate-pulse shrink-0 ml-2">Live</x-badge>
                        @elseif($jadwal->status == 'selesai')
                            <x-badge class="bg-green-500/10 text-green-500 border-green-500/20 text-[10px] uppercase tracking-wider shrink-0 ml-2">Selesai</x-badge>
                        @elseif($jadwal->status == 'dijadwalkan')
                            <x-badge class="bg-amber-500/10 text-amber-500 border-amber-500/20 text-[10px] uppercase tracking-wider shrink-0 ml-2">Mendatang</x-badge>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm text-muted-foreground mb-4">
                        <div class="flex items-center gap-2">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $jadwal->nama_lokasi ?? 'Tidak ada lokasi' }}
                        </div>
                    </div>

                    {{-- Stat chip --}}
                    <div class="flex items-center gap-2 bg-muted/30 rounded-lg px-3 py-2">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4 text-primary shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-sm"><span class="font-bold text-foreground">{{ $jadwal->pesertas_count }}</span> <span class="text-muted-foreground">Peserta Terdaftar</span></span>
                    </div>
                </div>

                <div class="p-4 border-t border-border/50 bg-muted/10">
                    <a href="{{ route('laporan.show', $jadwal->id) }}" class="w-full flex items-center justify-center gap-2 bg-primary/90 hover:bg-primary text-primary-foreground font-semibold py-2 px-4 rounded-lg text-sm transition-all hover:shadow-lg hover:shadow-primary/30">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Lihat Laporan
                    </a>
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-muted rounded-full flex items-center justify-center mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 text-muted-foreground"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-foreground">Belum Ada Jadwal</h3>
                <p class="text-muted-foreground max-w-sm mt-2">Buat jadwal I'tikaf terlebih dahulu untuk menghasilkan laporan presensi.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
