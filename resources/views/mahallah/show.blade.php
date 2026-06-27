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

    <!-- Daftar Tempat Ibadah Islam -->
    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg p-0 overflow-hidden mt-6">
        <x-slot name="header">
            <div class="flex justify-between items-center p-6 border-b border-border flex-wrap gap-4">
                <div>
                    <h3 class="text-lg font-semibold tracking-tight">Daftar Tempat Ibadah Islam</h3>
                    <p class="text-xs text-muted-foreground mt-0.5">Tempat ibadah yang terdaftar di bawah mahallah ini.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold">{{ $mahallah->tempatIbadahs->count() }} Terdaftar</span>
                    @if(Auth::user()->role === 'pengurus_inti')
                        <a href="{{ route('tempat-ibadah.create', ['mahallah_id' => $mahallah->id]) }}">
                            <x-button variant="default" class="text-xs py-1.5 px-3 flex items-center gap-1.5 shadow-md shadow-primary/10">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Tempat Ibadah
                            </x-button>
                        </a>
                    @endif
                </div>
            </div>
        </x-slot>
        
        <div class="overflow-x-auto">
            <x-table :headers="['No', 'Nama Tempat Ibadah', 'Jenis', 'Radius Presensi', 'Aksi']">
                @forelse($mahallah->tempatIbadahs as $index => $ti)
                <tr class="border-b border-border transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle text-muted-foreground">{{ $index + 1 }}</td>
                    <td class="p-4 align-middle font-semibold text-foreground">
                        <div class="flex items-center gap-3">
                            @if($ti->foto)
                                <img src="{{ asset('storage/' . $ti->foto) }}" alt="Foto {{ $ti->nama }}" class="w-10 h-10 object-cover rounded-lg border">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0">🕌</div>
                            @endif
                            <div>
                                <span class="font-bold text-foreground">{{ $ti->nama }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 align-middle text-muted-foreground capitalize">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium border border-primary/20">
                            {{ $ti->jenis }}
                        </span>
                    </td>
                    <td class="p-4 align-middle text-muted-foreground">{{ $ti->radius_meter }} Meter</td>
                    <td class="p-4 align-middle">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tempat-ibadah.show', $ti->id) }}" class="p-1.5 text-muted-foreground hover:text-primary transition-colors" title="Detail">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @if(Auth::user()->role === 'pengurus_inti')
                                <a href="{{ route('tempat-ibadah.edit', $ti->id) }}" class="p-1.5 text-muted-foreground hover:text-yellow-600 transition-colors" title="Edit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('tempat-ibadah.destroy', $ti->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tempat ibadah ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-muted-foreground hover:text-red-600 transition-colors" title="Hapus">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-muted-foreground">Belum ada tempat ibadah yang terdaftar di mahallah ini.</td>
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

        // Render Tempat Ibadah markers
        const tempatIbadahs = @json($mahallah->tempatIbadahs);
        tempatIbadahs.forEach(function(ti) {
            const tiIcon = MarkazMap.createIcon('bg-emerald-500');
            const popupContent = `
                <div class="space-y-1 text-xs" style="min-width: 150px;">
                    <div class="font-bold text-foreground">${ti.nama}</div>
                    <div class="text-muted-foreground capitalize">🕌 ${ti.jenis}</div>
                    ${ti.foto ? `<img src="/storage/${ti.foto}" class="w-full h-20 object-cover rounded-lg border mt-1 shadow-sm">` : ''}
                    <div class="pt-1.5 text-center">
                        <a href="/tempat-ibadah/${ti.id}" class="text-primary hover:underline font-semibold flex items-center justify-center gap-1">
                            Lihat Detail
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            `;
            L.marker([parseFloat(ti.latitude), parseFloat(ti.longitude)], {icon: tiIcon}).addTo(map)
                .bindPopup(popupContent);
        });
    });
</script>
@endif
@endpush

