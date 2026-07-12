@extends('layouts.app')

@section('title', 'Tambah Tempat Ibadah Islam - MARKAZ')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #map { height: 350px; border-radius: 0.75rem; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ $selectedMahallahId ? route('mahallah.show', $selectedMahallahId) : '/dashboard' }}" 
           class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Tambah Tempat Ibadah Islam</h1>
            <p class="text-muted-foreground mt-1 text-sm">Tambahkan tempat ibadah baru di bawah Mahallah.</p>
        </div>
    </div>

    @if ($errors->any())
        <x-alert type="danger" message="Terdapat kesalahan pada input Anda. Silakan periksa kembali." />
    @endif

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl relative overflow-hidden">
        <form action="{{ route('tempat-ibadah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-label for="nama">Nama Tempat Ibadah <span class="text-red-500">*</span></x-label>
                    <x-input id="nama" name="nama" type="text" placeholder="Contoh: Masjid Jami' Al-Ikhlas" value="{{ old('nama') }}" required />
                    @error('nama')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="jenis">Jenis Tempat Ibadah <span class="text-red-500">*</span></x-label>
                    <x-select id="jenis" name="jenis" required>
                        <option value="masjid" {{ old('jenis') == 'masjid' ? 'selected' : '' }}>Masjid</option>
                        <option value="langgar" {{ old('jenis') == 'langgar' ? 'selected' : '' }}>Langgar</option>
                        <option value="mushola" {{ old('jenis') == 'mushola' ? 'selected' : '' }}>Mushola</option>
                        <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </x-select>
                    @error('jenis')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-label for="mahallah_id">Mahallah Terkait <span class="text-red-500">*</span></x-label>
                    <x-select id="mahallah_id" name="mahallah_id" required>
                        <option value="">-- Pilih Mahallah --</option>
                        @foreach($mahallahs as $mahallah)
                            <option value="{{ $mahallah->id }}" 
                                    data-lat="{{ $mahallah->latitude }}" 
                                    data-lng="{{ $mahallah->longitude }}"
                                    {{ old('mahallah_id', $selectedMahallahId) == $mahallah->id ? 'selected' : '' }}>
                                {{ $mahallah->nama_mahallah }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('mahallah_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="radius_meter">Radius Geofencing Presensi (Meter) <span class="text-red-500">*</span></x-label>
                    <x-input id="radius_meter" name="radius_meter" type="number" min="1" placeholder="Contoh: 100" value="{{ old('radius_meter', 100) }}" required />
                    @error('radius_meter')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <x-label for="foto">Foto Tempat Ibadah</x-label>
                <input id="foto" name="foto" type="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" accept="image/*" />
                @error('foto')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Map -->
            <div class="space-y-3">
                <x-label>Peta Lokasi Geografis <span class="text-red-500">*</span></x-label>
                <p class="text-xs text-muted-foreground">Klik pada peta untuk menetapkan koordinat lokasi tempat ibadah secara presisi.</p>
                <div id="map" class="border border-border shadow-inner"></div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <x-label for="latitude" class="text-xs">Latitude</x-label>
                        <x-input id="latitude" name="latitude" type="text" value="{{ old('latitude') }}" class="text-xs h-8" required />
                    </div>
                    <div class="space-y-1">
                        <x-label for="longitude" class="text-xs">Longitude</x-label>
                        <x-input id="longitude" name="longitude" type="text" value="{{ old('longitude') }}" class="text-xs h-8" required />
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                <a href="{{ $selectedMahallahId ? route('mahallah.show', $selectedMahallahId) : '/dashboard' }}">
                    <x-button type="button" variant="outline">Batal</x-button>
                </a>
                <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40">Simpan Tempat Ibadah</x-button>
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
        
        let initialLat = document.getElementById('latitude').value;
        let initialLng = document.getElementById('longitude').value;
        
        // If not set, check selected Mahallah coordinates
        if (!initialLat || !initialLng) {
            const mahallahSelect = document.getElementById('mahallah_id');
            const selected = mahallahSelect.options[mahallahSelect.selectedIndex];
            if (selected) {
                initialLat = selected.getAttribute('data-lat');
                initialLng = selected.getAttribute('data-lng');
            }
        }
        
        const mapLat = initialLat || defaultLat;
        const mapLng = initialLng || defaultLng;
        const initialZoom = initialLat ? 16 : 5;

        // Initialize map
        const map = MarkazMap.init('map', [mapLat, mapLng], initialZoom);

        let marker = null;
        let circle = null;
        const customIcon = MarkazMap.createIcon('bg-primary');

        if (initialLat && initialLng) {
            updateMap(parseFloat(initialLat), parseFloat(initialLng));
        }

        function updateMap(lat, lng, centerMap = true) {
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
            if (centerMap) {
                map.setView([lat, lng], map.getZoom() < 16 ? 16 : map.getZoom());
            }
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

        // Listen for manual coordinate inputs
        function handleManualInput() {
            const latVal = parseFloat(document.getElementById('latitude').value);
            const lngVal = parseFloat(document.getElementById('longitude').value);

            if (!isNaN(latVal) && !isNaN(lngVal)) {
                updateMap(latVal, lngVal, true);
            }
        }

        document.getElementById('latitude').addEventListener('input', handleManualInput);
        document.getElementById('longitude').addEventListener('input', handleManualInput);

        // Listen for mahallah selection to center map
        document.getElementById('mahallah_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const lat = selected.getAttribute('data-lat');
            const lng = selected.getAttribute('data-lng');

            if (lat && lng) {
                updateMap(parseFloat(lat), parseFloat(lng));
                updateInputs(parseFloat(lat), parseFloat(lng));
            }
        });

        // Listen for radius changes
        document.getElementById('radius_meter').addEventListener('input', function() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                updateCircle(lat, lng);
            }
        });

        // Map Click
        map.on('click', function(e) {
            updateMap(e.latlng.lat, e.latlng.lng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        // Add Geocoder
        L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Cari lokasi...",
        })
        .on('markgeocode', function(e) {
            const latlng = e.geocode.center;
            updateMap(latlng.lat, latlng.lng);
            updateInputs(latlng.lat, latlng.lng);
        })
        .addTo(map);
    });
</script>
@endpush
