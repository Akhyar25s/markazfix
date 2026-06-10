@extends('layouts.app')

@section('title', 'Edit Jadwal')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #map { height: 400px; border-radius: 0.75rem; }
    .geocoder-control { z-index: 1000; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ route('jadwal.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Edit Jadwal</h1>
            <p class="text-muted-foreground mt-1 text-sm">Ubah detail kegiatan I'tikaf yang sudah ada.</p>
        </div>
    </div>

    @if ($errors->any())
        <x-alert type="danger" message="Terdapat kesalahan pada input Anda. Silakan periksa kembali." />
    @endif

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <x-label for="nama_itikaf">Nama Kegiatan <span class="text-red-500">*</span></x-label>
                <x-input id="nama_itikaf" name="nama_itikaf" type="text" placeholder="Contoh: I'tikaf Ramadhan 1445H Gelombang 1" value="{{ old('nama_itikaf', $jadwal->nama_itikaf) }}" required />
                @error('nama_itikaf')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="keterangan">Keterangan</x-label>
                <x-textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukkan detail kegiatan atau persyaratan (opsional)">{{ old('keterangan', $jadwal->keterangan) }}</x-textarea>
                @error('keterangan')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <x-label for="tanggal_mulai">Tanggal Mulai <span class="text-red-500">*</span></x-label>
                    <x-input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai', $jadwal->tanggal_mulai->format('Y-m-d')) }}" required />
                    @error('tanggal_mulai')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <x-label for="tanggal_selesai">Tanggal Selesai <span class="text-red-500">*</span></x-label>
                    <x-input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai', $jadwal->tanggal_selesai->format('Y-m-d')) }}" required />
                    @error('tanggal_selesai')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="status">Status Kegiatan <span class="text-red-500">*</span></x-label>
                    <x-select id="status" name="status" required>
                        <option value="dijadwalkan" {{ old('status', $jadwal->status) == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="berlangsung" {{ old('status', $jadwal->status) == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status', $jadwal->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </x-select>
                    @error('status')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-label for="nama_lokasi">Nama Lokasi / Mahallah <span class="text-red-500">*</span></x-label>
                    <x-select id="nama_lokasi" name="nama_lokasi" required>
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($mahallahs as $mahallah)
                            <option value="{{ $mahallah->nama_mahallah }}" 
                                    data-lat="{{ $mahallah->latitude }}" 
                                    data-lng="{{ $mahallah->longitude }}"
                                    {{ old('nama_lokasi', $jadwal->nama_lokasi) == $mahallah->nama_mahallah ? 'selected' : '' }}>
                                {{ $mahallah->nama_mahallah }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('nama_lokasi')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <x-label for="radius_meter">Radius Presensi (Meter) <span class="text-red-500">*</span></x-label>
                    <x-input id="radius_meter" name="radius_meter" type="number" min="1" placeholder="Contoh: 100" value="{{ old('radius_meter', $jadwal->radius_meter) }}" required />
                    @error('radius_meter')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Map Configuration Section -->
            <div class="space-y-3">
                <x-label>Konfigurasi Geofencing <span class="text-red-500">*</span></x-label>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs text-muted-foreground">Tentukan titik koordinat presensi. Lokasi akan otomatis terisi saat Mahallah dipilih.</p>
                </div>
                
                <div id="map" class="border border-border shadow-inner"></div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <x-label for="latitude" class="text-xs">Latitude</x-label>
                        <x-input id="latitude" name="latitude" type="text" value="{{ old('latitude', $jadwal->latitude) }}" readonly class="bg-muted text-xs h-8" />
                    </div>
                    <div class="space-y-1">
                        <x-label for="longitude" class="text-xs">Longitude</x-label>
                        <x-input id="longitude" name="longitude" type="text" value="{{ old('longitude', $jadwal->longitude) }}" readonly class="bg-muted text-xs h-8" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                <a href="{{ route('jadwal.index') }}">
                    <x-button type="button" variant="outline">Batal</x-button>
                </a>
                <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40">Perbarui Jadwal</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="{{ asset('js/map-utils.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = -2.5489;
        const defaultLng = 118.0149;
        const initialLat = parseFloat(document.getElementById('latitude').value) || defaultLat;
        const initialLng = parseFloat(document.getElementById('longitude').value) || defaultLng;
        const initialZoom = document.getElementById('latitude').value ? 16 : 5;

        // Initialize Map using Utility
        const map = MarkazMap.init('map', [initialLat, initialLng], initialZoom);

        let marker = null;
        let circle = null;
        const customIcon = MarkazMap.createIcon('bg-primary');

        if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
            updateMap(initialLat, initialLng);
        }

        function updateMap(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { 
                    draggable: true,
                    icon: customIcon
                }).addTo(map);
                
                marker.on('dragend', function(e) {
                    const pos = marker.getLatLng();
                    updateInputs(pos.lat, pos.lng);
                    updateCircle(pos.lat, pos.lng);
                });
            }

            updateCircle(lat, lng);
            map.setView([lat, lng], map.getZoom() < 16 ? 16 : map.getZoom());
            updateInputs(lat, lng);
        }

        function updateCircle(lat, lng) {
            const radius = parseInt(document.getElementById('radius_meter').value) || 100;
            if (circle) {
                circle.setLatLng([lat, lng]);
                circle.setRadius(radius);
            } else {
                circle = MarkazMap.createGeofence([lat, lng], radius).addTo(map);
            }
        }

        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        }

        // Listen for mahallah selection
        document.getElementById('nama_lokasi').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const lat = selected.getAttribute('data-lat');
            const lng = selected.getAttribute('data-lng');

            if (lat && lng) {
                updateMap(parseFloat(lat), parseFloat(lng));
            }
        });

        // Listen for radius changes
        document.getElementById('radius_meter').addEventListener('input', function() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            if (lat && lng) {
                updateCircle(parseFloat(lat), parseFloat(lng));
            }
        });

        // Map Click
        map.on('click', function(e) {
            updateMap(e.latlng.lat, e.latlng.lng);
        });

        // Add Geocoder
        L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Cari lokasi...",
        })
        .on('markgeocode', function(e) {
            const latlng = e.geocode.center;
            updateMap(latlng.lat, latlng.lng);
        })
        .addTo(map);
    });
</script>
@endpush
