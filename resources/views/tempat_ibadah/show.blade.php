@extends('layouts.app')

@section('title', 'Detail Tempat Ibadah Islam - MARKAZ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('mahallah.show', $tempatIbadah->mahallah_id) }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-foreground">Detail Tempat Ibadah Islam</h1>
                <p class="text-muted-foreground mt-1 text-sm">Informasi rinci tempat ibadah di bawah {{ $tempatIbadah->mahallah->nama_mahallah }}.</p>
            </div>
        </div>
        
        @if(Auth::user()->role === 'pengurus_inti')
        <div class="flex gap-2">
            <a href="{{ route('tempat-ibadah.edit', $tempatIbadah->id) }}">
                <x-button variant="default" class="flex items-center gap-2 shadow-primary/30 shadow-lg">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Tempat Ibadah
                </x-button>
            </a>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Info Card -->
        <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -z-10 pointer-events-none"></div>
            
            <div class="space-y-6 p-2">
                @if($tempatIbadah->foto)
                    <div>
                        <div class="text-sm text-muted-foreground mb-2">Foto Tempat Ibadah</div>
                        <img src="{{ asset('storage/' . $tempatIbadah->foto) }}" alt="Foto {{ $tempatIbadah->nama }}" class="w-full max-h-60 object-cover rounded-xl border shadow-md">
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-muted-foreground uppercase tracking-wider font-semibold mb-1">Nama</div>
                        <div class="font-bold text-lg text-foreground">{{ $tempatIbadah->nama }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted-foreground uppercase tracking-wider font-semibold mb-1">Jenis</div>
                        <div class="capitalize font-medium text-foreground">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                                🕌 {{ $tempatIbadah->jenis }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-border">
                    <div>
                        <div class="text-xs text-muted-foreground uppercase tracking-wider font-semibold mb-1">Mahallah Induk</div>
                        <a href="{{ route('mahallah.show', $tempatIbadah->mahallah_id) }}" class="font-medium text-primary hover:underline flex items-center gap-1">
                            {{ $tempatIbadah->mahallah->nama_mahallah }}
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                    <div>
                        <div class="text-xs text-muted-foreground uppercase tracking-wider font-semibold mb-1">Radius Presensi</div>
                        <div class="font-medium text-foreground">{{ $tempatIbadah->radius_meter }} Meter</div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Peta Geografis (Embed Iframe) -->
        <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg flex flex-col">
            <x-slot name="header">
                <h3 class="text-lg font-semibold tracking-tight">Lokasi Geografis (Google Maps)</h3>
            </x-slot>
            
            <div class="space-y-4 flex-1 flex flex-col justify-between">
                <div class="rounded-xl border border-border bg-muted/30 overflow-hidden relative min-h-[300px]">
                    {{-- Google Maps Embed iframe --}}
                    <iframe
                        src="https://maps.google.com/maps?q={{ $tempatIbadah->latitude }},{{ $tempatIbadah->longitude }}&z=16&output=embed"
                        width="100%" height="320" style="border:0; border-radius:0.75rem;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <a href="https://maps.google.com/?q={{ $tempatIbadah->latitude }},{{ $tempatIbadah->longitude }}" target="_blank" class="absolute bottom-4 right-4 z-[1000] text-xs font-medium bg-background/80 px-3 py-1.5 rounded-full border border-border hover:bg-background transition-colors flex items-center gap-1 shadow-md backdrop-blur-sm">
                        Buka di Google Maps
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
