@extends('layouts.app')

@section('title', 'Pendaftaran Peserta')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Daftar Jadwal I'tikaf</h1>
            <p class="text-muted-foreground mt-1 text-sm sm:text-base">Pilih jadwal I'tikaf untuk mendaftarkan peserta dari wilayah Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif
    
    @if(session('error'))
        <x-alert type="danger" message="{{ session('error') }}" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jadwals as $jadwal)
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg hover:shadow-primary/20 transition-all flex flex-col group relative overflow-hidden">
                <!-- Status indicator line -->
                <div class="absolute top-0 left-0 w-full h-1 
                    {{ $jadwal->status == 'berlangsung' ? 'bg-blue-500' : 'bg-amber-500' }}">
                </div>
                
                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="space-y-1">
                            <h3 class="font-bold text-lg leading-tight group-hover:text-primary transition-colors line-clamp-1" title="{{ $jadwal->nama_itikaf }}">
                                {{ $jadwal->nama_itikaf }}
                            </h3>
                            <p class="text-xs text-muted-foreground flex items-center gap-1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $jadwal->nama_lokasi ?? 'Tidak diketahui' }}
                            </p>
                        </div>
                        
                        @if($jadwal->status == 'dijadwalkan')
                            <x-badge class="bg-amber-500/10 text-amber-500 border-amber-500/20 text-[10px] uppercase tracking-wider">Dijadwalkan</x-badge>
                        @elseif($jadwal->status == 'berlangsung')
                            <x-badge class="bg-blue-500/10 text-blue-500 border-blue-500/20 text-[10px] uppercase tracking-wider animate-pulse">Berlangsung</x-badge>
                        @endif
                    </div>
                    
                    <div class="text-sm text-muted-foreground line-clamp-2 mb-4 h-10">
                        {{ $jadwal->keterangan ?: 'Tidak ada keterangan' }}
                    </div>
                    
                    <div class="space-y-2 text-sm bg-muted/30 p-3 rounded-lg border border-border/50">
                        <div class="flex justify-between items-center">
                            <span class="text-muted-foreground flex items-center gap-1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Pelaksanaan
                            </span>
                            <span class="font-medium text-right text-xs">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-border/50 mt-2">
                            <span class="text-muted-foreground flex items-center gap-1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Radius (m)
                            </span>
                            <span class="font-medium text-foreground">{{ $jadwal->radius_meter }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 border-t border-border/50 bg-muted/10">
                    <a href="{{ route('peserta.create', $jadwal->id) }}" class="w-full block">
                        <x-button class="w-full flex items-center justify-center gap-2">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Daftarkan Peserta
                        </x-button>
                    </a>
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-muted rounded-full flex items-center justify-center mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 text-muted-foreground"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-foreground">Tidak Ada Jadwal Aktif</h3>
                <p class="text-muted-foreground max-w-md mt-2">Saat ini belum ada jadwal I'tikaf yang berstatus 'Dijadwalkan' atau 'Berlangsung'. Silakan tunggu hingga pengurus inti membuat jadwal baru.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
