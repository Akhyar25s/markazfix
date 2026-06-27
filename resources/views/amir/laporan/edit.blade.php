@extends('layouts.app')

@section('title', 'Edit Laporan Sesi - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('amir.laporan.show', $laporan->jadwal_itikaf_id) }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Edit Laporan Sesi</h1>
            <p class="text-sm text-muted-foreground mt-0.5">{{ $laporan->jadwal->nama_itikaf }}</p>
        </div>
    </div>

    {{-- Catatan Revisi --}}
    @if($laporan->status === 'dikembalikan_wilayah' && $laporan->catatan_wilayah)
    <div class="p-4 rounded-xl bg-orange-50 border border-orange-200 text-orange-700 text-sm">
        <p class="font-bold mb-1">⚠️ Catatan Revisi dari Pengurus Wilayah:</p>
        <p>{{ $laporan->catatan_wilayah }}</p>
    </div>
    @endif
    @if($laporan->status === 'dikembalikan_inti' && $laporan->catatan_inti)
    <div class="p-4 rounded-xl bg-orange-50 border border-orange-200 text-orange-700 text-sm">
        <p class="font-bold mb-1">⚠️ Catatan Revisi dari Pengurus Inti:</p>
        <p>{{ $laporan->catatan_inti }}</p>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('amir.laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="glass-card p-8 rounded-2xl space-y-6 border border-white/60">

            @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Nama Sesi (Dropdown 6 Sesi Tetap) --}}
            <div class="space-y-2">
                <label for="nama_sesi" class="block text-sm font-bold text-foreground/80">Sesi Kegiatan <span class="text-red-500">*</span></label>
                <select id="nama_sesi" name="nama_sesi" required
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                    <option value="">-- Pilih Sesi --</option>
                    <option value="Bayan Subuh" {{ old('nama_sesi', $laporan->nama_sesi) == 'Bayan Subuh' ? 'selected' : '' }}>🌅 Bayan Subuh</option>
                    <option value="Talim Pagi" {{ old('nama_sesi', $laporan->nama_sesi) == 'Talim Pagi' ? 'selected' : '' }}>☀️ Talim Pagi</option>
                    <option value="Talim Zhuhur" {{ old('nama_sesi', $laporan->nama_sesi) == 'Talim Zhuhur' ? 'selected' : '' }}>🕛 Talim Zhuhur</option>
                    <option value="Talim Ashar" {{ old('nama_sesi', $laporan->nama_sesi) == 'Talim Ashar' ? 'selected' : '' }}>🕓 Talim Ashar</option>
                    <option value="Bayan Maghrib" {{ old('nama_sesi', $laporan->nama_sesi) == 'Bayan Maghrib' ? 'selected' : '' }}>🌇 Bayan Maghrib</option>
                    <option value="Talim Akhir" {{ old('nama_sesi', $laporan->nama_sesi) == 'Talim Akhir' ? 'selected' : '' }}>🌙 Talim Akhir</option>
                </select>
            </div>

            {{-- Waktu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="waktu_mulai" class="block text-sm font-bold text-foreground/80">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="waktu_mulai" name="waktu_mulai"
                        value="{{ old('waktu_mulai', \Carbon\Carbon::parse($laporan->waktu_mulai)->format('Y-m-d\TH:i')) }}" required
                        class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                </div>
                <div class="space-y-2">
                    <label for="waktu_selesai" class="block text-sm font-bold text-foreground/80">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="waktu_selesai" name="waktu_selesai"
                        value="{{ old('waktu_selesai', \Carbon\Carbon::parse($laporan->waktu_selesai)->format('Y-m-d\TH:i')) }}" required
                        class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                </div>
            </div>

            {{-- Uraian --}}
            <div class="space-y-2">
                <label for="uraian_kegiatan" class="block text-sm font-bold text-foreground/80">Uraian Kegiatan <span class="text-red-500">*</span></label>
                <textarea id="uraian_kegiatan" name="uraian_kegiatan" rows="5" required
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground resize-none">{{ old('uraian_kegiatan', $laporan->uraian_kegiatan) }}</textarea>
            </div>

            {{-- Daftar Hadir --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-bold text-foreground/80">Peserta yang Bertugas</label>
                    <span id="hadir-count" class="text-xs font-semibold text-primary bg-primary/10 px-3 py-1 rounded-full">0 dipilih</span>
                </div>
                @if($peserta->isEmpty())
                <p class="text-sm text-muted-foreground italic">Belum ada peserta terdaftar.</p>
                @else
                <div class="border border-border/60 rounded-xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto bg-white/30">
                    @foreach($peserta as $p)
                    <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white/60 cursor-pointer transition-colors">
                        <input type="checkbox" name="peserta_hadir[]" value="{{ $p->id }}"
                            {{ in_array($p->id, old('peserta_hadir', $laporan->peserta_hadir ?? [])) ? 'checked' : '' }}
                            class="peserta-checkbox w-4 h-4 rounded text-primary accent-primary"
                            onchange="updateHadirCount()">
                        <span class="text-sm font-medium text-foreground">{{ $p->name }}</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Dokumen yang sudah ada --}}
            @if(!empty($laporan->dokumen_pendukung))
            <div class="space-y-3">
                <label class="block text-sm font-bold text-foreground/80">Dokumen Saat Ini</label>
                <div class="space-y-2">
                    @foreach($laporan->dokumen_pendukung as $idx => $dok)
                    <div class="flex items-center gap-3 p-3 bg-white/60 rounded-xl border border-border/50">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-sm font-medium text-foreground flex-1 truncate">{{ $dok['nama'] }}</span>
                        <a href="{{ Storage::url($dok['path']) }}" target="_blank" class="text-xs text-primary hover:underline font-semibold shrink-0">Lihat</a>
                        <form action="{{ route('amir.laporan.hapus-dokumen', $laporan->id) }}" method="POST" class="shrink-0">
                            @csrf
                            <input type="hidden" name="index" value="{{ $idx }}">
                            <button type="submit" onclick="return confirm('Hapus dokumen ini?')"
                                class="text-xs text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upload Dokumen Baru --}}
            <div class="space-y-2">
                <label class="block text-sm font-bold text-foreground/80">
                    Tambah Dokumen Baru
                    <span class="text-xs font-normal text-muted-foreground ml-1">(Opsional, maks. 5 total)</span>
                </label>
                <div class="border-2 border-dashed border-border/60 rounded-xl p-5 text-center hover:border-primary/40 transition-colors bg-white/20">
                    <input type="file" id="dokumen" name="dokumen[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden" onchange="previewFiles(this)">
                    <label for="dokumen" class="cursor-pointer flex flex-col items-center gap-2">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-primary">Klik untuk upload file baru</span>
                    </label>
                </div>
                <div id="file-preview" class="space-y-2 hidden">
                    <div id="file-list" class="space-y-1"></div>
                </div>
            </div>

        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('amir.laporan.show', $laporan->jadwal_itikaf_id) }}"
               class="px-6 py-3 bg-white border border-border text-sm font-semibold text-foreground rounded-xl hover:bg-gray-50 transition-all">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/25 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
window.addEventListener('load', () => updateHadirCount());

function updateHadirCount() {
    const count = document.querySelectorAll('.peserta-checkbox:checked').length;
    document.getElementById('hadir-count').textContent = count + ' dipilih';
}

function previewFiles(input) {
    const preview = document.getElementById('file-preview');
    const list = document.getElementById('file-list');
    list.innerHTML = '';
    if (input.files.length > 0) {
        preview.classList.remove('hidden');
        Array.from(input.files).forEach(file => {
            const sizeKb = (file.size / 1024).toFixed(1);
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2 p-2 bg-white/60 rounded-lg text-sm';
            div.innerHTML = `<svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span class="font-medium text-foreground truncate">${file.name}</span><span class="text-muted-foreground shrink-0">${sizeKb} KB</span>`;
            list.appendChild(div);
        });
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endpush
