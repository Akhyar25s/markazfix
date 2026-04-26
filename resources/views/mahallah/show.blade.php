@extends('layouts.app')

@section('title', 'Detail Mahallah')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('mahallah.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-foreground">Detail Mahallah</h1>
                <p class="text-muted-foreground mt-1 text-sm sm:text-base">Informasi lengkap mengenai {{ $mahallah->nama_mahallah }}.</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mahallah.edit', $mahallah->id) }}">
                <x-button variant="default" class="flex items-center gap-2 shadow-primary/30 shadow-lg">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Mahallah
                </x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informasi Mahallah -->
        <div class="space-y-6">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg relative overflow-hidden h-full">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>
                
                <x-slot name="header">
                    <h3 class="text-lg font-semibold tracking-tight">Informasi Dasar</h3>
                </x-slot>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Nama Mahallah</div>
                        <div class="font-medium text-lg">{{ $mahallah->nama_mahallah }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Wilayah Induk</div>
                        @if($mahallah->wilayah)
                            <a href="{{ route('wilayah.show', $mahallah->wilayah->id) }}" class="inline-flex items-center gap-1 font-medium text-primary hover:underline">
                                {{ $mahallah->wilayah->nama_wilayah }}
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            <div class="text-sm text-muted-foreground italic">Tidak ada wilayah</div>
                        @endif
                    </div>

                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Alamat</div>
                        <div class="text-sm {{ $mahallah->alamat ? 'text-foreground' : 'text-muted-foreground italic' }}">
                            {{ $mahallah->alamat ?: 'Tidak ada alamat' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Status</div>
                        @if($mahallah->status == 'aktif')
                            <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">Aktif</x-badge>
                        @else
                            <x-badge variant="danger" class="bg-red-500/10 text-red-500 border-red-500/20">Nonaktif</x-badge>
                        @endif
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Peta/Lokasi -->
        <div class="space-y-6">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg h-full flex flex-col">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold tracking-tight">Lokasi Geografis</h3>
                </x-slot>
                
                <div class="space-y-4 flex-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-muted-foreground mb-1">Latitude</div>
                            <div class="font-medium font-mono text-sm">{{ $mahallah->latitude ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted-foreground mb-1">Longitude</div>
                            <div class="font-medium font-mono text-sm">{{ $mahallah->longitude ?: '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-border bg-muted/30 h-48 flex flex-col items-center justify-center text-muted-foreground overflow-hidden relative">
                        @if($mahallah->latitude && $mahallah->longitude)
                            <div class="absolute inset-0 bg-[url('https://api.maptiler.com/maps/basic-v2/static/{{ $mahallah->longitude }},{{ $mahallah->latitude }},14/600x400.png?key=get_your_own_OpIi9ZULNHzrESv6T2vL')] bg-cover bg-center opacity-50 mix-blend-luminosity"></div>
                            <div class="relative z-10 w-10 h-10 text-primary drop-shadow-md">
                                <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"></path></svg>
                            </div>
                            <a href="https://maps.google.com/?q={{ $mahallah->latitude }},{{ $mahallah->longitude }}" target="_blank" class="relative z-10 mt-2 text-xs font-medium bg-background/80 px-3 py-1 rounded-full border border-border hover:bg-background transition-colors flex items-center gap-1 shadow-sm">
                                Buka di Google Maps
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 mb-2 opacity-50"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm">Koordinat peta belum diatur</p>
                        @endif
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
