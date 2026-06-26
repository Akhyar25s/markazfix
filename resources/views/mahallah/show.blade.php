@extends('layouts.app')

@section('title', 'Detail Mahallah')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 300px; border-radius: 0.75rem; width: 100%; }
</style>
@endpush

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            @if(Auth::user()->role === 'pengurus_inti')
                <a href="{{ route('mahallah.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            @else
                <a href="/dashboard" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-foreground">Detail Mahallah</h1>
                <p class="text-muted-foreground mt-1 text-sm sm:text-base">Informasi lengkap mengenai {{ $mahallah->nama_mahallah }}.</p>
            </div>
        </div>
        @if(Auth::user()->role === 'pengurus_inti')
        <div class="flex gap-2">
            <a href="{{ route('mahallah.edit', $mahallah->id) }}">
                <x-button variant="default" class="flex items-center gap-2 shadow-primary/30 shadow-lg">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Mahallah
                </x-button>
            </a>
        </div>
        @endif
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
                    @if($mahallah->foto)
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Foto</div>
                        <img src="{{ asset('storage/' . $mahallah->foto) }}" alt="Foto {{ $mahallah->nama_mahallah }}" class="w-full max-h-52 object-cover rounded-xl border border-border shadow">
                    </div>
                    @endif
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Nama Mahallah</div>
                        <div class="font-medium text-lg">{{ $mahallah->nama_mahallah }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Wilayah Induk</div>
                        @if($mahallah->wilayah)
                            @if(Auth::user()->role === 'pengurus_inti')
                                <a href="{{ route('wilayah.show', $mahallah->wilayah->id) }}" class="inline-flex items-center gap-1 font-medium text-primary hover:underline">
                                    {{ $mahallah->wilayah->nama_wilayah }}
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @else
                                <span class="font-medium text-foreground">{{ $mahallah->wilayah->nama_wilayah }}</span>
                            @endif
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
                    <div class="mt-2 rounded-xl border border-border bg-muted/30 flex flex-col items-center justify-center text-muted-foreground overflow-hidden relative min-h-[300px]">
                        @if($mahallah->latitude && $mahallah->longitude)
                            <div id="map" class="hidden"></div>
                            {{-- Google Maps Embed (no API key needed) --}}
                            <iframe
                                src="https://maps.google.com/maps?q={{ $mahallah->latitude }},{{ $mahallah->longitude }}&z=16&output=embed"
                                width="100%" height="300" style="border:0; border-radius:0.75rem;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            <a href="https://maps.google.com/?q={{ $mahallah->latitude }},{{ $mahallah->longitude }}" target="_blank" class="absolute bottom-4 right-4 z-[1000] text-xs font-medium bg-background/80 px-3 py-1.5 rounded-full border border-border hover:bg-background transition-colors flex items-center gap-1 shadow-md backdrop-blur-sm">
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

    <!-- Daftar Anggota Mahallah -->
    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg p-0 overflow-hidden">
        <x-slot name="header">
            <div class="flex justify-between items-center p-6 border-b border-border">
                <h3 class="text-lg font-semibold tracking-tight">Daftar Anggota (Jamaah)</h3>
                <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold">{{ $mahallah->users->count() }} Orang</span>
            </div>
        </x-slot>
        
        <div class="overflow-x-auto">
            <x-table :headers="['No', 'Nama Lengkap', 'Email', 'No. Telepon', 'Status']">
                @forelse($mahallah->users as $index => $anggota)
                <tr class="border-b border-border transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle text-muted-foreground">{{ $index + 1 }}</td>
                    <td class="p-4 align-middle font-semibold text-foreground">{{ $anggota->name }}</td>
                    <td class="p-4 align-middle text-muted-foreground">{{ $anggota->email }}</td>
                    <td class="p-4 align-middle text-muted-foreground">{{ $anggota->no_telepon ?: '-' }}</td>
                    <td class="p-4 align-middle">
                        @if($anggota->status === 'aktif')
                            <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">Aktif</x-badge>
                        @else
                            <x-badge variant="danger" class="bg-red-500/10 text-red-500 border-red-500/20">Nonaktif</x-badge>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-muted-foreground">Belum ada anggota yang terdaftar di mahallah ini.</td>
                </tr>
                @endforelse
            </x-table>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
@if($mahallah->latitude && $mahallah->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/map-utils.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $mahallah->latitude }};
        const lng = {{ $mahallah->longitude }};
        
        // Initialize Map using Utility
        const map = MarkazMap.init('map', [lat, lng], 15);
        map.scrollWheelZoom.disable();

        // Use custom icon from Utility
        const customIcon = MarkazMap.createIcon('bg-primary');

        L.marker([lat, lng], {icon: customIcon}).addTo(map)
            .bindPopup(`<div class="font-bold text-center">${ @json($mahallah->nama_mahallah) }</div>`)
            .openPopup();
    });
</script>
@endif
@endpush

