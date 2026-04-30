@extends('layouts.app')

@section('title', 'Edit Mahallah')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #map { height: 400px; border-radius: 0.75rem; }
    .geocoder-control { z-index: 1000; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ route('mahallah.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Edit Mahallah</h1>
            <p class="text-muted-foreground mt-1 text-sm">Ubah data mahallah yang sudah ada.</p>
        </div>
    </div>

    @if ($errors->any())
        <x-alert type="danger" message="Terdapat kesalahan pada input Anda. Silakan periksa kembali." />
    @endif

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl">
        <form action="{{ route('mahallah.update', $mahallah->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <x-label for="nama_mahallah">Nama Mahallah <span class="text-red-500">*</span></x-label>
                <x-input id="nama_mahallah" name="nama_mahallah" type="text" placeholder="Masukkan nama mahallah" value="{{ old('nama_mahallah', $mahallah->nama_mahallah) }}" required />
                @error('nama_mahallah')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="wilayah_id">Pilih Wilayah <span class="text-red-500">*</span></x-label>
                <x-select id="wilayah_id" name="wilayah_id" required>
                    <option value="">-- Pilih Wilayah --</option>
                    @foreach($wilayahs as $wilayah)
                        <option value="{{ $wilayah->id }}" {{ old('wilayah_id', $mahallah->wilayah_id) == $wilayah->id ? 'selected' : '' }}>
                            {{ $wilayah->nama_wilayah }}
                        </option>
                    @endforeach
                </x-select>
                @error('wilayah_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="alamat">Alamat</x-label>
                <x-textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap mahallah">{{ old('alamat', $mahallah->alamat) }}</x-textarea>
                @error('alamat')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Map Picker Section -->
            <div class="space-y-3">
                <x-label>Lokasi di Peta <span class="text-red-500">*</span></x-label>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs text-muted-foreground">Klik pada peta atau gunakan kotak pencarian untuk mengubah lokasi mahallah.</p>
                    <x-button type="button" variant="outline" size="sm" id="btn-locate" class="text-xs flex items-center gap-1 h-8">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Gunakan Lokasi Saya
                    </x-button>
                </div>
                
                <div id="map" class="border border-border shadow-inner"></div>
                
                <div class="hidden">
                    <x-input id="latitude" name="latitude" type="hidden" value="{{ old('latitude', $mahallah->latitude) }}" />
                    <x-input id="longitude" name="longitude" type="hidden" value="{{ old('longitude', $mahallah->longitude) }}" />
                </div>
            </div>

            <div class="space-y-2">
                <x-label for="status">Status <span class="text-red-500">*</span></x-label>
                <x-select id="status" name="status" required>
                    <option value="aktif" {{ old('status', $mahallah->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $mahallah->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </x-select>
                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                <a href="{{ route('mahallah.index') }}">
                    <x-button type="button" variant="outline">Batal</x-button>
                </a>
                <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40">Perbarui Mahallah</x-button>
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
        const defaultLat = {{ $mahallah->latitude ?? -2.5489 }};
        const defaultLng = {{ $mahallah->longitude ?? 118.0149 }};
        const initialZoom = {{ $mahallah->latitude ? 15 : 5 }};

        // Initialize Map using Utility
        const map = MarkazMap.init('map', [defaultLat, defaultLng], initialZoom);

        // Marker for location
        let marker = null;
        const customIcon = MarkazMap.createIcon('bg-primary');
        const alamatInput = document.getElementById('alamat');
        const engine = L.Control.Geocoder.nominatim();

        if (defaultLat && defaultLng) {
            marker = L.marker([defaultLat, defaultLng], { 
                draggable: true,
                icon: customIcon
            }).addTo(map);
            
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }

        function updateCoordinates(lat, lng, reverseGeocode = false) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            if (reverseGeocode) {
                engine.reverse({lat, lng}, map.getZoom(), function(results) {
                    if (results && results.length > 0) {
                        alamatInput.value = results[0].name;
                    }
                });
            }
        }

        function moveMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, { 
                    draggable: true,
                    icon: customIcon
                }).addTo(map);
                
                marker.on('dragend', function(event) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            }
            map.setView(latlng, 15);
            updateCoordinates(latlng.lat, latlng.lng);
        }

        // Click on map to place/move marker
        map.on('click', function(e) {
            moveMarker(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng, true);
        });

        // Locate Me Feature
        const btnLocate = document.getElementById('btn-locate');
        btnLocate.addEventListener('click', function() {
            btnLocate.disabled = true;
            btnLocate.innerText = 'Mencari...';
            map.locate({setView: true, maxZoom: 16});
        });

        map.on('locationfound', function(e) {
            moveMarker(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng, true);
            btnLocate.disabled = false;
            btnLocate.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Gunakan Lokasi Saya';
        });

        map.on('locationerror', function(e) {
            alert("Gagal mendapatkan lokasi: " + e.message);
            btnLocate.disabled = false;
            btnLocate.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Gunakan Lokasi Saya';
        });

        // Add Geocoder (Search)
        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Cari lokasi atau alamat...",
            errorMessage: "Lokasi tidak ditemukan."
        })
        .on('markgeocode', function(e) {
            const latlng = e.geocode.center;
            moveMarker(latlng);
        })
        .addTo(map);

        // Auto-geocode from address field (Debounced)
        let geocodeTimeout;
        alamatInput.addEventListener('input', function() {
            clearTimeout(geocodeTimeout);
            const query = this.value.trim();
            
            if (query.length < 5) return;

            geocodeTimeout = setTimeout(() => {
                engine.geocode(query, function(results) {
                    if (results && results.length > 0) {
                        const topResult = results[0];
                        moveMarker(topResult.center);
                    }
                });
            }, 1000);
        });
    });
</script>
@endpush

