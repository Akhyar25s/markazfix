@extends('layouts.app')

@section('title', isset($jenisKegiatan) ? 'Edit Jenis Kegiatan' : 'Tambah Jenis Kegiatan')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('jenis-kegiatan.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">{{ isset($jenisKegiatan) ? 'Edit' : 'Tambah' }} Jenis Kegiatan</h1>
            <p class="text-sm text-muted-foreground mt-0.5">Formulir master data jenis kegiatan.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="glass-card p-8 rounded-2xl border border-white/60 shadow-lg shadow-primary/5">
        <form action="{{ isset($jenisKegiatan) ? route('jenis-kegiatan.update', $jenisKegiatan->id) : route('jenis-kegiatan.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($jenisKegiatan))
                @method('PUT')
            @endif

            {{-- Input: Nama Kegiatan --}}
            <div>
                <label for="nama_kegiatan" class="block text-sm font-bold text-foreground/80 mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" required
                       value="{{ old('nama_kegiatan', $jenisKegiatan->nama_kegiatan ?? '') }}"
                       placeholder="Misal: Duduk Ta'lim Majelis"
                       class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                @error('nama_kegiatan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input: Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-bold text-foreground/80 mb-2">Deskripsi (Opsional)</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          placeholder="Penjelasan singkat mengenai kegiatan ini..."
                          class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground resize-none">{{ old('deskripsi', $jenisKegiatan->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input: Status --}}
            <div>
                <label class="block text-sm font-bold text-foreground/80 mb-3">Status</label>
                <div class="flex gap-4">
                    <label class="relative flex items-center gap-3 p-4 bg-white/60 border border-border rounded-xl cursor-pointer hover:bg-white transition-all w-1/2 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                        <input type="radio" name="status" value="aktif" class="w-4 h-4 text-primary focus:ring-primary/20"
                               {{ old('status', $jenisKegiatan->status ?? 'aktif') === 'aktif' ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-bold text-foreground">Aktif</p>
                            <p class="text-xs text-muted-foreground mt-0.5">Dapat digunakan anggota</p>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-3 p-4 bg-white/60 border border-border rounded-xl cursor-pointer hover:bg-white transition-all w-1/2 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 has-[:checked]:ring-1 has-[:checked]:ring-red-500">
                        <input type="radio" name="status" value="nonaktif" class="w-4 h-4 text-red-500 focus:ring-red-500/20"
                               {{ old('status', $jenisKegiatan->status ?? '') === 'nonaktif' ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-bold text-foreground">Nonaktif</p>
                            <p class="text-xs text-muted-foreground mt-0.5">Disembunyikan dari daftar</p>
                        </div>
                    </label>
                </div>
                @error('status')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-border/50">
                <a href="{{ route('jenis-kegiatan.index') }}" class="px-5 py-2.5 bg-white border border-border text-sm font-semibold text-foreground rounded-xl hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
