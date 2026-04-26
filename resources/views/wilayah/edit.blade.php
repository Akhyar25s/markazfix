@extends('layouts.app')

@section('title', 'Edit Wilayah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ route('wilayah.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Edit Wilayah</h1>
            <p class="text-muted-foreground mt-1 text-sm">Ubah data wilayah yang sudah ada.</p>
        </div>
    </div>

    @if ($errors->any())
        <x-alert type="danger" message="Terdapat kesalahan pada input Anda. Silakan periksa kembali." />
    @endif

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl">
        <form action="{{ route('wilayah.update', $wilayah->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <x-label for="nama_wilayah">Nama Wilayah <span class="text-red-500">*</span></x-label>
                <x-input id="nama_wilayah" name="nama_wilayah" type="text" placeholder="Masukkan nama wilayah (contoh: Wilayah 1)" value="{{ old('nama_wilayah', $wilayah->nama_wilayah) }}" required />
                @error('nama_wilayah')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="deskripsi">Deskripsi</x-label>
                <x-textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi wilayah (opsional)">{{ old('deskripsi', $wilayah->deskripsi) }}</x-textarea>
                @error('deskripsi')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="pengurus_id">Pengurus Wilayah</x-label>
                <x-select id="pengurus_id" name="pengurus_id">
                    <option value="">-- Pilih Pengurus (Opsional) --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('pengurus_id', $wilayah->pengurus_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-select>
                @error('pengurus_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-label for="status">Status <span class="text-red-500">*</span></x-label>
                <x-select id="status" name="status" required>
                    <option value="aktif" {{ old('status', $wilayah->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $wilayah->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </x-select>
                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                <a href="{{ route('wilayah.index') }}">
                    <x-button type="button" variant="outline">Batal</x-button>
                </a>
                <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40">Perbarui Wilayah</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
