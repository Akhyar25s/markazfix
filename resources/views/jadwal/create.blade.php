@extends('layouts.app')

@section('title', 'Buat Jadwal Baru')

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
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Buat Jadwal Baru</h1>
            <p class="text-muted-foreground mt-1 text-sm">Tambahkan jadwal kegiatan I'tikaf baru ke dalam sistem.</p>
        </div>
    </div>

    @if ($errors->any())
        <x-alert type="danger" message="Terdapat kesalahan pada input Anda. Silakan periksa kembali." />
    @endif

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <form action="{{ route('jadwal.store') }}" method="POST" class="space-y-6 relative z-10" onsubmit="const btn = this.querySelector('button[type=submit]'); btn.innerHTML = 'Simpan Jadwal...'; setTimeout(() => btn.disabled = true, 50);">
            @csrf

            <div class="space-y-2">
                <x-label for="nama_itikaf">Nama Kegiatan <span class="text-red-500">*</span></x-label>
                <x-input id="nama_itikaf" name="nama_itikaf" type="text" placeholder="Contoh: I'tikaf Ramadhan 1445H Gelombang 1" value="{{ old('nama_itikaf') }}" required />
                @error('nama_itikaf')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="keterangan">Keterangan</x-label>
                <x-textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukkan detail kegiatan atau persyaratan (opsional)">{{ old('keterangan') }}</x-textarea>
                @error('keterangan')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-label for="tanggal_mulai">Tanggal Mulai <span class="text-red-500">*</span></x-label>
                    <x-input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai') }}" required />
                    @error('tanggal_mulai')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <x-label for="tanggal_selesai">Tanggal Selesai <span class="text-red-500">*</span></x-label>
                    <x-input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai') }}" required />
                    @error('tanggal_selesai')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-label for="mahallah_id">Pilih Mahallah <span class="text-red-500">*</span></x-label>
                    <x-select id="mahallah_id" name="mahallah_id" required>
                        <option value="">-- Pilih Mahallah --</option>
                        @foreach($mahallahs as $mahallah)
                            <option value="{{ $mahallah->id }}" {{ old('mahallah_id') == $mahallah->id ? 'selected' : '' }}>
                                {{ $mahallah->nama_mahallah }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('mahallah_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <x-label for="tempat_ibadah_id">Pilih Tempat Ibadah Islam <span class="text-red-500">*</span></x-label>
                    <x-select id="tempat_ibadah_id" name="tempat_ibadah_id" required>
                        <option value="">-- Pilih Tempat Ibadah --</option>
                    </x-select>
                    @error('tempat_ibadah_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Map Configuration Section -->
            <div class="space-y-3">
                <x-label>Preview Lokasi & Geofencing</x-label>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs text-muted-foreground">Lokasi dan radius absensi terisi otomatis berdasarkan tempat ibadah yang dipilih.</p>
                </div>
                
                <div id="map" class="border border-border shadow-inner"></div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <x-label class="text-xs">Latitude</x-label>
                        <x-input id="latitude" name="latitude" type="text" readonly class="bg-muted text-xs h-8" />
                    </div>
                    <div class="space-y-1">
                        <x-label class="text-xs">Longitude</x-label>
                        <x-input id="longitude" name="longitude" type="text" readonly class="bg-muted text-xs h-8" />
                    </div>
                    <div class="space-y-1">
                        <x-label class="text-xs">Radius Absen (Meter)</x-label>
                        <x-input id="radius_meter" name="radius_meter" type="text" readonly class="bg-muted text-xs h-8" />
                    </div>
                </div>
            </div>

            <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 flex gap-3 text-blue-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5 shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm">
                    <p class="font-medium">Catatan Pembuatan Jadwal</p>
                    <p class="mt-1 opacity-90">Jadwal yang dibuat otomatis akan berstatus <strong>Dijadwalkan</strong>. Pengurus Wilayah akan dapat melihat jadwal ini dan mendaftarkan pesertanya.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                <a href="{{ route('jadwal.index') }}">
                    <x-button type="button" variant="outline">Batal</x-button>
                </a>
                <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40">Simpan Jadwal</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/map-utils.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = -2.5489;
        const defaultLng = 118.0149;
        
        // Initialize Map using Utility
        const map = MarkazMap.init('map', [defaultLat, defaultLng], 5);

        let marker = null;
        let circle = null;
        const customIcon = MarkazMap.createIcon('bg-primary');

        const mahallahs = @json($mahallahs);
        const mahallahSelect = document.getElementById('mahallah_id');
        const tempatSelect = document.getElementById('tempat_ibadah_id');

        function updateMapPreview(lat, lng, radius) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            }

            if (circle) {
                circle.setLatLng([lat, lng]);
                circle.setRadius(radius);
            } else {
                circle = MarkazMap.createGeofence([lat, lng], radius).addTo(map);
            }

            map.setView([lat, lng], 16);
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            document.getElementById('radius_meter').value = radius + ' m';
        }

        mahallahSelect.addEventListener('change', function() {
            const mahallahId = this.value;
            tempatSelect.innerHTML = '<option value="">-- Pilih Tempat Ibadah --</option>';
            
            if (!mahallahId) return;

            const selectedMahallah = mahallahs.find(m => m.id == mahallahId);
            if (selectedMahallah && selectedMahallah.tempat_ibadahs) {
                selectedMahallah.tempat_ibadahs.forEach(function(ti) {
                    const option = document.createElement('option');
                    option.value = ti.id;
                    option.textContent = ti.nama + ' (' + ti.jenis + ')';
                    option.setAttribute('data-lat', ti.latitude);
                    option.setAttribute('data-lng', ti.longitude);
                    option.setAttribute('data-radius', ti.radius_meter);
                    tempatSelect.appendChild(option);
                });
            }
        });

        tempatSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (!selected.value) return;

            const lat = parseFloat(selected.getAttribute('data-lat'));
            const lng = parseFloat(selected.getAttribute('data-lng'));
            const radius = parseInt(selected.getAttribute('data-radius'));

            if (lat && lng) {
                updateMapPreview(lat, lng, radius);
            }
        });

        // Trigger change on load if old value exists
        if (mahallahSelect.value) {
            mahallahSelect.dispatchEvent(new Event('change'));
            // Wait a moment for options to render then select old value if exists
            const oldTempatIbadahId = "{{ old('tempat_ibadah_id') }}";
            if (oldTempatIbadahId) {
                tempatSelect.value = oldTempatIbadahId;
                tempatSelect.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endpush
