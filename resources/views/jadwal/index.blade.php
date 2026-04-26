@extends('layouts.app')

@section('title', 'Jadwal I\'tikaf')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Jadwal I'tikaf</h1>
            <p class="text-muted-foreground mt-1 text-sm sm:text-base">Kelola dan pantau jadwal pelaksanaan I'tikaf di berbagai Mahallah.</p>
        </div>
        <a href="{{ route('jadwal.create') }}">
            <x-button variant="default" class="flex items-center gap-2 shadow-primary/30 shadow-lg hover:shadow-primary/50 hover:scale-105 transition-all">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Jadwal Baru
            </x-button>
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jadwals as $jadwal)
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg hover:shadow-primary/20 transition-all flex flex-col group relative overflow-hidden">
                <!-- Status indicator line -->
                <div class="absolute top-0 left-0 w-full h-1 
                    {{ $jadwal->status == 'berlangsung' ? 'bg-blue-500' : ($jadwal->status == 'dijadwalkan' ? 'bg-amber-500' : ($jadwal->status == 'dibatalkan' ? 'bg-red-500' : 'bg-green-500')) }}">
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
                        @elseif($jadwal->status == 'selesai')
                            <x-badge class="bg-green-500/10 text-green-500 border-green-500/20 text-[10px] uppercase tracking-wider">Selesai</x-badge>
                        @else
                            <x-badge class="bg-red-500/10 text-red-500 border-red-500/20 text-[10px] uppercase tracking-wider">Dibatalkan</x-badge>
                        @endif
                    </div>
                    
                    <div class="text-sm text-muted-foreground line-clamp-2 mb-4 h-10">
                        {{ $jadwal->keterangan ?: 'Tidak ada keterangan' }}
                    </div>
                    
                    <div class="space-y-2 text-sm bg-muted/30 p-3 rounded-lg border border-border/50">
                        <div class="flex justify-between items-center">
                            <span class="text-muted-foreground flex items-center gap-1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Mulai
                            </span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-muted-foreground flex items-center gap-1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Selesai
                            </span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</span>
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
                
                <div class="p-4 border-t border-border/50 bg-muted/10 flex justify-between items-center">
                    <div class="text-xs text-muted-foreground flex items-center gap-1">
                        Dibuat oleh: <span class="font-medium text-foreground">{{ $jadwal->pembuat->name ?? 'Sistem' }}</span>
                    </div>
                    <a href="#" class="text-sm font-medium text-primary hover:underline flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Detail <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-muted rounded-full flex items-center justify-center mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 text-muted-foreground"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-foreground">Belum Ada Jadwal</h3>
                <p class="text-muted-foreground max-w-md mt-2">Anda belum membuat jadwal i'tikaf apapun. Silakan buat jadwal baru untuk memulai kegiatan.</p>
                <a href="{{ route('jadwal.create') }}" class="mt-6">
                    <x-button>Buat Jadwal Sekarang</x-button>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
