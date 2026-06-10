@extends('layouts.app')

@section('title', 'Buat Laporan Sesi - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('amir.laporan.show', $jadwal->id) }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Buat Laporan Sesi</h1>
            <p class="text-sm text-muted-foreground mt-0.5">{{ $jadwal->nama_itikaf }}</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('amir.laporan.store', $jadwal->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="glass-card p-8 rounded-2xl space-y-6 border border-white/60">

            {{-- Errors --}}
            @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Nama Sesi --}}
            <div class="space-y-2">
                <label for="nama_sesi" class="block text-sm font-bold text-foreground/80">Nama Sesi Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_sesi" name="nama_sesi" value="{{ old('nama_sesi') }}" required
                    placeholder="Contoh: Sesi Qiyamul Lail Malam ke-3"
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground placeholder:text-muted-foreground/50">
            </div>

            {{-- Waktu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="waktu_mulai" class="block text-sm font-bold text-foreground/80">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                        class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                </div>
                <div class="space-y-2">
                    <label for="waktu_selesai" class="block text-sm font-bold text-foreground/80">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required
                        class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground">
                </div>
            </div>

            {{-- Uraian Kegiatan --}}
            <div class="space-y-2">
                <label for="uraian_kegiatan" class="block text-sm font-bold text-foreground/80">Uraian Kegiatan <span class="text-red-500">*</span></label>
                <textarea id="uraian_kegiatan" name="uraian_kegiatan" rows="5" required
                    placeholder="Ceritakan kegiatan yang berlangsung selama sesi ini..."
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground placeholder:text-muted-foreground/50 resize-none">{{ old('uraian_kegiatan') }}</textarea>
            </div>

            {{-- Daftar Peserta Hadir --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-bold text-foreground/80">Peserta yang Hadir</label>
                    <span id="hadir-count" class="text-xs font-semibold text-primary bg-primary/10 px-3 py-1 rounded-full">0 dipilih</span>
                </div>
                @if($peserta->isEmpty())
                <p class="text-sm text-muted-foreground italic">Belum ada peserta terdaftar pada i'tikaf ini.</p>
                @else
                <div class="border border-border/60 rounded-xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto bg-white/30">
                    @foreach($peserta as $p)
                    <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white/60 cursor-pointer transition-colors">
                        <input type="checkbox" name="peserta_hadir[]" value="{{ $p->id }}"
                            {{ in_array($p->id, old('peserta_hadir', [])) ? 'checked' : '' }}
                            class="peserta-checkbox w-4 h-4 rounded text-primary focus:ring-primary/20 accent-primary"
                            onchange="updateHadirCount()">
                        <span class="text-sm font-medium text-foreground">{{ $p->name }}</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Upload Dokumen --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-foreground/80">
                    Dokumen Pendukung
                    <span class="text-xs font-normal text-muted-foreground ml-1">(Opsional, maks. 5 file, format: JPG, PNG, PDF, maks 10MB/file)</span>
                </label>
                <div class="border-2 border-dashed border-border/60 rounded-xl p-6 text-center hover:border-primary/40 transition-colors bg-white/20">
                    <input type="file" id="dokumen" name="dokumen[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden" onchange="previewFiles(this)">
                    <label for="dokumen" class="cursor-pointer flex flex-col items-center gap-2">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-primary">Klik untuk upload</span>
                        <span class="text-xs text-muted-foreground">atau drag & drop file di sini</span>
                    </label>
                </div>
                <div id="file-preview" class="space-y-2 hidden">
                    <p class="text-xs font-bold text-foreground/70">File yang akan diupload:</p>
                    <div id="file-list" class="space-y-1"></div>
                </div>
            </div>

        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('amir.laporan.show', $jadwal->id) }}"
               class="px-6 py-3 bg-white border border-border text-sm font-semibold text-foreground rounded-xl hover:bg-gray-50 transition-all">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/25 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Sebagai Draft
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
function updateHadirCount() {
    const count = document.querySelectorAll('.peserta-checkbox:checked').length;
    document.getElementById('hadir-count').textContent = count + ' dipilih';
}

function previewFiles(input) {
    const preview = document.getElementById('file-preview');
    const list = document.getElementById('file-list');
    const files = input.files;

    if (files.length > 5) {
        alert('Maksimal 5 file yang bisa diupload.');
        input.value = '';
        return;
    }

    list.innerHTML = '';
    if (files.length > 0) {
        preview.classList.remove('hidden');
        Array.from(files).forEach(file => {
            const sizeKb = (file.size / 1024).toFixed(1);
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2 p-2 bg-white/60 rounded-lg text-sm';
            div.innerHTML = `
                <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="font-medium text-foreground truncate">${file.name}</span>
                <span class="text-muted-foreground shrink-0">${sizeKb} KB</span>
            `;
            list.appendChild(div);
        });
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endpush
