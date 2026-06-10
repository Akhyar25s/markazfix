@extends('layouts.app')

@section('title', 'Global Dashboard - Markaz')

@section('content')
<div class="space-y-8 animate-fade-in pb-8">
    <!-- Welcome Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-foreground">Assalamu'alaikum, {{ Auth::user()->name ?? 'Admin' }}</h1>
            
            @if(Auth::user()->role !== 'anggota')
            <div class="flex gap-2">
                <a href="{{ route('export.anggota', 'pdf') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold rounded-xl border border-red-200 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Data Anggota (PDF)
                </a>
                <a href="{{ route('export.anggota', 'excel') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-green-50 text-green-600 hover:bg-green-100 text-sm font-semibold rounded-xl border border-green-200 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Data Anggota (Excel)
                </a>
            </div>
            @endif
        </div>
        <p class="text-lg text-muted-foreground">Berikut adalah ringkasan aktivitas dan laporan yang membutuhkan perhatian Anda hari ini.</p>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary border border-primary/20">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                @if(Auth::user()->role !== 'anggota')
                <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">Aktif</x-badge>
                @endif
            </div>
            <div>
                <div class="text-sm text-muted-foreground font-semibold uppercase tracking-wider mb-1">
                    {{ Auth::user()->role === 'anggota' ? 'I\'tikaf Diikuti' : 'Total Anggota' }}
                </div>
                <div class="text-3xl font-black text-foreground">
                    {{ Auth::user()->role === 'anggota' ? count($jadwalSaya ?? []) : number_format($totalAnggota ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </x-card>

        <!-- Stat Card 2 -->
        <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 rounded-full bg-accent/10 flex items-center justify-center text-accent border border-accent/20">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-sm text-muted-foreground font-semibold uppercase tracking-wider mb-1">I'tikaf Berjalan</div>
                <div class="text-3xl font-black text-foreground">{{ $itikafBerjalan ?? 0 }}</div>
                @if(Auth::user()->role === 'pengurus_inti')
                <div class="text-xs text-muted-foreground mt-1">{{ $itikafDijadwalkan ?? 0 }} Dijadwalkan</div>
                @endif
            </div>
        </x-card>

        <!-- Stat Card 3 -->
        <x-card class="backdrop-blur-md bg-card/80 border-red-500/20 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-2 h-full bg-red-500"></div>
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 rounded-full bg-red-500/10 flex items-center justify-center text-red-500 border border-red-500/20">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-sm text-muted-foreground font-semibold uppercase tracking-wider mb-1">
                    {{ Auth::user()->role === 'anggota' ? 'Total Kehadiran' : 'Menunggu Approval' }}
                </div>
                <div class="text-3xl font-black text-red-500">
                    {{ Auth::user()->role === 'anggota' ? ($totalHadir ?? 0) : ($laporanMenunggu ?? 0) }}
                </div>
            </div>
        </x-card>

        <!-- Stat Card 4 (Dinamis berdasarkan Role) -->
        <x-card class="bg-gradient-to-br from-primary to-accent border-0 shadow-lg shadow-primary/30 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIi8+PC9zdmc+')] opacity-20"></div>
            <div class="flex flex-col h-full justify-between relative z-10">
                <div class="flex justify-between items-start">
                    <div>
                        @if(Auth::user()->role === 'anggota')
                            @php
                                $totalTarget = 0;
                                $totalCapaian = 0;
                                if(isset($progressKegiatan)) {
                                    foreach($progressKegiatan as $prog) {
                                        $totalTarget += $prog['target'];
                                        $totalCapaian += $prog['capaian'];
                                    }
                                }
                                $percentGlobal = $totalTarget > 0 ? min(100, round(($totalCapaian / $totalTarget) * 100)) : 0;
                            @endphp
                            <div class="text-sm text-white/80 font-semibold uppercase tracking-wider mb-1">Target Individual</div>
                            <div class="text-4xl font-black">{{ $percentGlobal }}%</div>
                            <div class="text-xs text-white/80 mt-1">{{ $totalCapaian }}/{{ $totalTarget }} Terlaksana</div>
                        @elseif(Auth::user()->role === 'pengurus_wilayah')
                            <div class="text-sm text-white/80 font-semibold uppercase tracking-wider mb-1">Total Peserta I'tikaf</div>
                            <div class="text-4xl font-black">{{ $totalPesertaItikaf ?? 0 }}</div>
                            <div class="text-xs text-white/80 mt-1">Dari Wilayah Anda</div>
                        @else
                            <div class="text-sm text-white/80 font-semibold uppercase tracking-wider mb-1">Kegiatan Global (Bulan Ini)</div>
                            <div class="text-4xl font-black">{{ collect($kegiatanGlobal ?? [])->sum('total') }}</div>
                            <div class="text-xs text-white/80 mt-1">Aktivitas Terlaksana</div>
                        @endif
                    </div>
                    <!-- Progress Ring -->
                    <div class="relative w-14 h-14 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90 drop-shadow-md" viewBox="0 0 36 36">
                            <path class="text-white/20" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4"></path>
                            <path class="text-white" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-dasharray="{{ Auth::user()->role === 'anggota' ? ($percentGlobal ?? 0) : 100 }}, 100" stroke-width="4" stroke-linecap="round"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Geospatial Dashboard Section -->
    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl p-0 overflow-hidden flex flex-col relative">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 z-0 pointer-events-none"></div>
        <div class="relative z-10">
            <div class="p-6 border-b border-border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-muted/30">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center text-primary shadow-inner">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-xl tracking-tight">Peta Persebaran Mahallah</h2>
                        <p class="text-sm text-muted-foreground">Lokasi geografis masjid/mahallah di seluruh wilayah.</p>
                    </div>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <select id="wilayah-filter" class="w-full sm:w-48 bg-background border border-input text-foreground text-sm rounded-xl focus:ring-2 focus:ring-primary/50 focus:border-primary block p-2.5 outline-none shadow-sm transition-all">
                        <option value="">Semua Wilayah</option>
                        <!-- Options can be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="p-0 relative">
                <div id="mahallah-map" class="w-full h-[500px] z-10 bg-slate-900/20"></div>
                
                <!-- Map loading overlay -->
                <div id="map-loading" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-background/50 backdrop-blur-sm transition-opacity duration-300">
                    <div class="h-12 w-12 rounded-full border-4 border-primary/30 border-t-primary animate-spin"></div>
                    <p class="mt-4 font-medium text-primary animate-pulse">Memuat data lokasi...</p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Bottom Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Table Section -->
        <div class="lg:col-span-2">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg h-full p-0 overflow-hidden">
                <div class="p-6 border-b border-border flex justify-between items-center bg-muted/30">
                    <h2 class="font-bold text-lg">Antrean Persetujuan Laporan</h2>
                    <a href="#" class="text-primary hover:text-primary/80 font-semibold text-sm flex items-center gap-1 transition-colors">
                        Lihat Semua <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
                @if(Auth::user()->role !== 'anggota')
                <x-table :headers="['Sesi I\'tikaf', 'Nama Amir', 'Wilayah', 'Status', 'Aksi']">
                    @forelse($laporanTerbaru as $laporan)
                    <tr class="border-b border-border transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle font-medium text-foreground">{{ $laporan->jadwal->nama_kegiatan ?? 'I\'tikaf' }}</td>
                        <td class="p-4 align-middle text-muted-foreground">{{ $laporan->amir->name ?? '-' }}</td>
                        <td class="p-4 align-middle text-muted-foreground">{{ $laporan->jadwal->mahallah->wilayah->nama ?? '-' }}</td>
                        <td class="p-4 align-middle">
                            @if($laporan->status === 'menunggu_inti')
                                <x-badge variant="danger" class="bg-red-500/10 text-red-500 border-red-500/20">Menunggu Inti</x-badge>
                            @elseif($laporan->status === 'menunggu_wilayah')
                                <x-badge variant="warning" class="bg-yellow-500/10 text-yellow-500 border-yellow-500/20">Menunggu Wilayah</x-badge>
                            @else
                                <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">{{ ucfirst($laporan->status) }}</x-badge>
                            @endif
                        </td>
                        <td class="p-4 align-middle">
                            <a href="/laporan/{{ $laporan->id }}">
                                <x-button variant="outline" class="h-8 px-3 text-xs">Review</x-button>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-muted-foreground">Tidak ada laporan terbaru.</td>
                    </tr>
                    @endforelse
                </x-table>
                @else
                <x-table :headers="['Kegiatan', 'Lokasi', 'Tanggal', 'Aksi']">
                    @forelse($jadwalSaya as $jadwal)
                    <tr class="border-b border-border transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle font-medium text-foreground">I'tikaf</td>
                        <td class="p-4 align-middle text-muted-foreground">-</td>
                        <td class="p-4 align-middle text-muted-foreground">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</td>
                        <td class="p-4 align-middle">
                            <x-button variant="outline" class="h-8 px-3 text-xs">Lihat</x-button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-muted-foreground">Belum ada i'tikaf yang diikuti.</td>
                    </tr>
                    @endforelse
                </x-table>
                @endif
            </x-card>
        </div>

        <!-- Side Section: Schedule -->
        <div class="lg:col-span-1">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg h-full flex flex-col">
                <x-slot name="header">
                    <h2 class="font-bold text-lg tracking-tight">Jadwal Mendatang</h2>
                </x-slot>
                
                <div class="space-y-4 flex-1">
                    @forelse($jadwalMendatang as $jadwal)
                    <div class="flex gap-4 items-start p-4 rounded-xl bg-muted/30 border border-border/50 hover:bg-muted/50 hover:border-primary/30 transition-all cursor-pointer group">
                        <div class="flex flex-col items-center justify-center bg-primary text-primary-foreground rounded-lg w-14 h-14 flex-shrink-0 shadow-md group-hover:scale-105 transition-transform">
                            <span class="text-[10px] font-bold uppercase tracking-wider opacity-80">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('M') }}</span>
                            <span class="text-xl font-black leading-none mt-0.5">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d') }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h4 class="font-semibold text-foreground group-hover:text-primary transition-colors">I'tikaf</h4>
                            <p class="text-sm text-muted-foreground flex items-center gap-1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $jadwal->mahallah->nama ?? 'Lokasi belum diset' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-muted-foreground text-center py-4">Belum ada jadwal i'tikaf mendatang.</p>
                    @endforelse
                </div>
                
                <div class="mt-6 pt-4 border-t border-border">
                    <x-button variant="outline" class="w-full">Lihat Kalender Lengkap</x-button>
                </div>
            </x-card>

            @if(Auth::user()->role === 'anggota')
            <!-- Progress Kegiatan (Khusus Anggota) -->
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg mt-6">
                <x-slot name="header">
                    <h2 class="font-bold text-lg tracking-tight">Progres Target Kegiatan</h2>
                </x-slot>
                
                <div class="space-y-5">
                    @forelse($progressKegiatan ?? [] as $prog)
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <span class="text-sm font-semibold text-foreground">{{ $prog['nama'] }}</span>
                            <span class="text-xs font-medium text-muted-foreground">{{ $prog['capaian'] }} / {{ $prog['target'] }}</span>
                        </div>
                        <div class="w-full bg-muted/50 rounded-full h-2.5 overflow-hidden border border-border/50">
                            <div class="bg-gradient-to-r from-primary to-accent h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $prog['persentase'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-muted-foreground text-center py-4">Belum ada target kegiatan yang diset untuk Anda bulan ini.</p>
                    @endforelse
                </div>
                
                <div class="mt-5 text-center">
                    <a href="{{ route('absensi-kegiatan.index') }}" class="text-xs font-semibold text-primary hover:underline flex items-center justify-center gap-1">
                        Input Kegiatan Baru
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </x-card>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    /* Leaflet popup customization to match glassmorphism theme */
    .leaflet-popup-content-wrapper {
        background-color: rgba(var(--card), 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        color: hsl(var(--foreground));
        border: 1px solid rgba(var(--border), 0.5);
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-tip {
        background-color: rgba(var(--card), 0.9);
        backdrop-filter: blur(12px);
    }
    .leaflet-popup-content {
        margin: 0;
        line-height: 1.5;
    }
    .leaflet-container a.leaflet-popup-close-button {
        top: 10px;
        right: 10px;
        color: hsl(var(--muted-foreground));
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--muted), 0.5);
        transition: all 0.2s ease;
    }
    .leaflet-container a.leaflet-popup-close-button:hover {
        color: hsl(var(--destructive));
        background: rgba(var(--destructive), 0.1);
    }
    
    /* Removed dark mode map tiles filter for natural satellite view */
    
    .leaflet-control-zoom a {
        background-color: rgba(var(--card), 0.9) !important;
        color: hsl(var(--foreground)) !important;
        border-color: rgba(var(--border), 0.5) !important;
        backdrop-filter: blur(8px);
    }
    .leaflet-control-zoom a:hover {
        background-color: rgba(var(--muted), 1) !important;
    }
</style>
@endpush

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="{{ asset('js/map-utils.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map using Utility, centered on Indonesia
        const map = MarkazMap.init('mahallah-map', [-2.5489, 118.0149], 5);

        // Add Search Control
        L.Control.geocoder({
            defaultMarkGeocode: true,
            placeholder: "Cari lokasi atau alamat...",
            errorMessage: "Lokasi tidak ditemukan."
        }).addTo(map);

        // Use custom icon from Utility
        const mahallahIcon = MarkazMap.createIcon('bg-primary', `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>`);

        var markersGroup = L.featureGroup();
        var filterSelect = document.getElementById('wilayah-filter');

        // Fetch data from API
        fetch('/api/mahallah-map')
            .then(response => response.json())
            .then(mahallahData => {
                // Populate filter options dynamically
                const wilayahs = [...new Set(mahallahData.map(item => item.wilayah))];
                wilayahs.forEach(wilayah => {
                    if (wilayah && wilayah !== 'Tidak Ada Wilayah') {
                        const option = document.createElement('option');
                        option.value = wilayah;
                        option.textContent = wilayah;
                        filterSelect.appendChild(option);
                    }
                });

                // Function to render markers
                function renderMarkers(dataToRender) {
                    markersGroup.clearLayers();
                    
                    dataToRender.forEach(function(mahallah) {
                        var statusColor = mahallah.status.toLowerCase() === 'aktif' 
                            ? 'bg-green-500/10 text-green-500 border border-green-500/20' 
                            : 'bg-red-500/10 text-red-500 border border-red-500/20';
                            
                        var popupContent = `
                            <div class="p-5 min-w-[240px] font-sans">
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-border/50">
                                    <h3 class="font-bold text-lg text-foreground tracking-tight">${mahallah.name}</h3>
                                    <span class="${statusColor} text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">${mahallah.status}</span>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-muted flex items-center justify-center text-muted-foreground">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider">Wilayah</p>
                                            <p class="text-sm font-semibold text-foreground">${mahallah.wilayah}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider">Anggota</p>
                                            <p class="text-sm font-semibold text-foreground">${mahallah.members} Jamaah</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-3 border-t border-border/50">
                                    <a href="/mahallah/${mahallah.id}" class="w-full flex items-center justify-center gap-2 bg-primary text-primary-foreground hover:bg-primary/90 transition-colors py-2 rounded-lg text-sm font-semibold shadow-md shadow-primary/20">
                                        Lihat Detail
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        `;

                        var marker = L.marker([mahallah.lat, mahallah.lng], {icon: mahallahIcon})
                            .bindPopup(popupContent, {
                                className: 'custom-popup border-0',
                                minWidth: 260
                            });
                        markersGroup.addLayer(marker);
                    });
                    
                    map.addLayer(markersGroup);

                    // Fit map bounds to markers if there are any
                    if (dataToRender.length > 0) {
                        map.fitBounds(markersGroup.getBounds(), {padding: [50, 50], maxZoom: 14});
                    }
                }

                // Initial render
                renderMarkers(mahallahData);
                
                // Hide loading overlay
                setTimeout(() => {
                    const loadingOverlay = document.getElementById('map-loading');
                    if(loadingOverlay) {
                        loadingOverlay.style.opacity = '0';
                        setTimeout(() => loadingOverlay.style.display = 'none', 300);
                    }
                }, 800);

                // Handle filter change
                filterSelect.addEventListener('change', function(e) {
                    const selectedWilayah = e.target.value;
                    const filteredData = selectedWilayah 
                        ? mahallahData.filter(m => m.wilayah === selectedWilayah)
                        : mahallahData;
                        
                    renderMarkers(filteredData);
                });
            })
            .catch(error => {
                console.error('Error fetching mahallah data:', error);
                document.getElementById('map-loading').innerHTML = '<p class="text-red-500 font-medium">Gagal memuat data peta.</p>';
            });
    });
</script>
@endpush
