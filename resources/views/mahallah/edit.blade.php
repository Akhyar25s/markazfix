@extends('layouts.app')

@section('title', 'Edit Mahallah')

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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-label for="latitude">Latitude (Koordinat Y)</x-label>
                    <x-input id="latitude" name="latitude" type="text" placeholder="Contoh: -6.200000" value="{{ old('latitude', $mahallah->latitude) }}" />
                    @error('latitude')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <x-label for="longitude">Longitude (Koordinat X)</x-label>
                    <x-input id="longitude" name="longitude" type="text" placeholder="Contoh: 106.816666" value="{{ old('longitude', $mahallah->longitude) }}" />
                    @error('longitude')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
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
