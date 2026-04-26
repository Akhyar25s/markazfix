@extends('layouts.app')

@section('title', 'Buat Jadwal Baru')

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
        
        <form action="{{ route('jadwal.store') }}" method="POST" class="space-y-6 relative z-10">
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
                    <x-label for="nama_lokasi">Nama Lokasi / Mahallah <span class="text-red-500">*</span></x-label>
                    <x-select id="nama_lokasi" name="nama_lokasi" required>
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($mahallahs as $mahallah)
                            <option value="{{ $mahallah->nama_mahallah }}" {{ old('nama_lokasi') == $mahallah->nama_mahallah ? 'selected' : '' }}>
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
                    <x-input id="radius_meter" name="radius_meter" type="number" min="1" placeholder="Contoh: 100" value="{{ old('radius_meter', 100) }}" required />
                    @error('radius_meter')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
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
