@extends('layouts.app')

@section('title', 'Tambah Mahallah')

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
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Tambah Mahallah</h1>
            <p class="text-muted-foreground mt-1 text-sm">Tambahkan data mahallah baru ke dalam sistem.</p>
        </div>
    </div>

    @if ($errors->any())
        <x-alert type="danger" message="Terdapat kesalahan pada input Anda. Silakan periksa kembali." />
    @endif

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl">
        <form action="{{ route('mahallah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-label for="nama_mahallah">Nama Mahallah <span class="text-red-500">*</span></x-label>
                <x-input id="nama_mahallah" name="nama_mahallah" type="text" placeholder="Masukkan nama mahallah (contoh: Mahallah Utsman)" value="{{ old('nama_mahallah') }}" required />
                @error('nama_mahallah')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="wilayah_id">Pilih Wilayah <span class="text-red-500">*</span></x-label>
                <x-select id="wilayah_id" name="wilayah_id" required>
                    <option value="">-- Pilih Wilayah --</option>
                    @foreach($wilayahs as $wilayah)
                        <option value="{{ $wilayah->id }}" {{ old('wilayah_id', request('wilayah_id')) == $wilayah->id ? 'selected' : '' }}>
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
                <x-textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap mahallah (opsional)">{{ old('alamat') }}</x-textarea>
                @error('alamat')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Upload -->
            <div class="space-y-2">
                <x-label for="foto">Foto Tempat Ibadah</x-label>
                <div id="foto-drop-zone" class="border-2 border-dashed border-border/60 rounded-xl p-5 text-center hover:border-primary/40 transition-colors bg-white/20 cursor-pointer">
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                    <label for="foto" class="cursor-pointer flex flex-col items-center gap-2">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-primary">Klik untuk upload foto</span>
                        <span class="text-xs text-muted-foreground">JPG, PNG, GIF, WebP (Maks. 5MB)</span>
                    </label>
                </div>
                <div id="foto-preview-container" class="hidden mt-3">
                    <img id="foto-preview" src="" class="w-full max-h-48 object-cover rounded-xl border border-border shadow-sm" alt="Preview Foto">
                    <button type="button" onclick="hapusFoto()" class="mt-2 text-xs text-red-500 hover:underline">Hapus foto</button>
                </div>
                @error('foto')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Map Picker Section -->
            <div class="space-y-3">
                <x-label>Pilih Lokasi di Peta <span class="text-red-500">*</span></x-label>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs text-muted-foreground">Klik pada peta atau gunakan kotak pencarian untuk menentukan lokasi mahallah.</p>
                    <x-button type="button" variant="outline" size="sm" id="btn-locate" class="text-xs flex items-center gap-1 h-8">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Gunakan Lokasi Saya
                    </x-button>
                </div>
                
                <div id="map" class="border border-border shadow-inner"></div>
                
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div class="space-y-1">
                        <x-label for="latitude">Latitude</x-label>
                        <x-input id="latitude" name="latitude" type="number" step="any" placeholder="Contoh: -3.3194" value="{{ old('latitude') }}" />
                    </div>
                    <div class="space-y-1">
                        <x-label for="longitude">Longitude</x-label>
                        <x-input id="longitude" name="longitude" type="number" step="any" placeholder="Contoh: 114.5908" value="{{ old('longitude') }}" />
                    </div>
                </div>
                <p class="text-xs text-muted-foreground mt-1">💡 Anda bisa klik di peta atau isi koordinat secara manual (ambil dari Google Maps).</p>
            </div>

            <div class="space-y-2">
                <x-label for="status">Status <span class="text-red-500">*</span></x-label>
                <x-select id="status" name="status" required>
                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </x-select>
                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                <a href="{{ request('wilayah_id') ? route('wilayah.show', request('wilayah_id')) : route('mahallah.index') }}">
                    <x-button type="button" variant="outline">Batal</x-button>
                </a>
                <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40">Simpan Mahallah</x-button>
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
        const initialLat = document.getElementById('latitude').value || defaultLat;
        const initialLng = document.getElementById('longitude').value || defaultLng;
        const initialZoom = document.getElementById('latitude').value ? 15 : 5;

        // Initialize Map using Utility
        const map = MarkazMap.init('map', [initialLat, initialLng], initialZoom);

        // Marker for location
        let marker = null;
        const customIcon = MarkazMap.createIcon('bg-primary');

        if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
            marker = L.marker([initialLat, initialLng], { 
                draggable: true,
                icon: customIcon
            }).addTo(map);
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
        const alamatInput = document.getElementById('alamat');
        const engine = L.Control.Geocoder.nominatim();
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
<script>
function previewFoto(input) {
    const container = document.getElementById('foto-preview-container');
    const preview = document.getElementById('foto-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusFoto() {
    document.getElementById('foto').value = '';
    document.getElementById('foto-preview-container').classList.add('hidden');
    document.getElementById('foto-preview').src = '';
}

// Sync input latitude/longitude manual dengan peta
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    function syncFromInputs() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            // Update map via event jika peta sudah terload
            if (typeof moveMarker !== 'undefined') {
                moveMarker(L.latLng(lat, lng));
            }
        }
    }
    
    if (latInput) latInput.addEventListener('change', syncFromInputs);
    if (lngInput) lngInput.addEventListener('change', syncFromInputs);
});
</script>
@endpush

