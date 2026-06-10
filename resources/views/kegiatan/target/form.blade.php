@extends('layouts.app')

@section('title', isset($targetKegiatan) ? 'Edit Target Kegiatan' : 'Tetapkan Target Kegiatan')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('target-kegiatan.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">{{ isset($targetKegiatan) ? 'Edit' : 'Tetapkan' }} Target Kegiatan</h1>
            <p class="text-sm text-muted-foreground mt-0.5">Tentukan kuota kegiatan individual untuk anggota.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="glass-card p-8 rounded-2xl border border-white/60 shadow-lg shadow-primary/5">
        <form action="{{ isset($targetKegiatan) ? route('target-kegiatan.update', $targetKegiatan->id) : route('target-kegiatan.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($targetKegiatan))
                @method('PUT')
            @endif

            {{-- Input: Jenis Kegiatan --}}
            <div>
                <label for="jenis_kegiatan_id" class="block text-sm font-bold text-foreground/80 mb-2">Jenis Kegiatan <span class="text-red-500">*</span></label>
                <select id="jenis_kegiatan_id" name="jenis_kegiatan_id" required
                        class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                    <option value="">-- Pilih Jenis Kegiatan --</option>
                    @foreach($jenisKegiatans as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kegiatan_id', $targetKegiatan->jenis_kegiatan_id ?? '') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_kegiatan }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_kegiatan_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Input: Jumlah Target --}}
                <div>
                    <label for="jumlah_target" class="block text-sm font-bold text-foreground/80 mb-2">Jumlah Target <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" id="jumlah_target" name="jumlah_target" required min="1"
                               value="{{ old('jumlah_target', $targetKegiatan->jumlah_target ?? '') }}"
                               placeholder="Contoh: 10"
                               class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground pr-12">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm text-muted-foreground font-semibold pointer-events-none">
                            Kali
                        </div>
                    </div>
                    @error('jumlah_target')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input: Periode --}}
                <div>
                    <label for="periode" class="block text-sm font-bold text-foreground/80 mb-2">Periode <span class="text-red-500">*</span></label>
                    <select id="periode" name="periode" required onchange="toggleBulan()"
                            class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                        <option value="bulanan" {{ old('periode', $targetKegiatan->periode ?? '') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ old('periode', $targetKegiatan->periode ?? '') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                    @error('periode')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Input: Tahun --}}
                <div>
                    <label for="tahun" class="block text-sm font-bold text-foreground/80 mb-2">Tahun Berlaku <span class="text-red-500">*</span></label>
                    <input type="number" id="tahun" name="tahun" required min="2024"
                           value="{{ old('tahun', $targetKegiatan->tahun ?? date('Y')) }}"
                           class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                    @error('tahun')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input: Bulan --}}
                <div id="bulan_container">
                    <label for="bulan" class="block text-sm font-bold text-foreground/80 mb-2">Bulan Berlaku</label>
                    <select id="bulan" name="bulan"
                            class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                        <option value="">-- Pilih Bulan --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('bulan', $targetKegiatan->bulan ?? date('n')) == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                    @error('bulan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-border/50">
                <a href="{{ route('target-kegiatan.index') }}" class="px-5 py-2.5 bg-white border border-border text-sm font-semibold text-foreground rounded-xl hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Target
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function toggleBulan() {
        const periode = document.getElementById('periode').value;
        const bulanContainer = document.getElementById('bulan_container');
        const bulanSelect = document.getElementById('bulan');
        
        if (periode === 'tahunan') {
            bulanContainer.classList.add('opacity-50', 'pointer-events-none');
            bulanSelect.required = false;
        } else {
            bulanContainer.classList.remove('opacity-50', 'pointer-events-none');
            bulanSelect.required = true;
        }
    }
    
    // Run on load
    document.addEventListener('DOMContentLoaded', toggleBulan);
</script>
@endsection
