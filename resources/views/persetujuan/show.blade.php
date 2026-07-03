@extends('layouts.app')

@section('title', 'Detail Laporan Sesi - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('persetujuan.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Detail Laporan Sesi</h1>
                <p class="text-sm text-muted-foreground mt-0.5">Tinjau dan berikan keputusan pada laporan ini</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('export.laporan-sesi', ['id' => $laporan->id, 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold rounded-xl border border-red-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                PDF
            </a>
            <a href="{{ route('export.laporan-sesi', ['id' => $laporan->id, 'format' => 'excel']) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-green-50 text-green-600 hover:bg-green-100 text-sm font-semibold rounded-xl border border-green-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Excel
            </a>
        </div>
    </div>

    {{-- Info Laporan --}}
    <div class="glass-card p-8 rounded-2xl border border-white/60 space-y-6">

        {{-- Meta Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6 border-b border-border/50">
            <div>
                <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-1">Nama Sesi</p>
                <p class="text-lg font-bold text-foreground">{{ $laporan->nama_sesi }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-1">Jadwal I'tikaf</p>
                <p class="text-base font-semibold text-foreground">{{ $laporan->jadwal->nama_itikaf }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-1">Amir Pelapor</p>
                <p class="text-base font-semibold text-foreground">{{ $laporan->amir->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-1">Waktu Sesi</p>
                <p class="text-base font-semibold text-foreground">
                    {{ \Carbon\Carbon::parse($laporan->waktu_mulai)->format('d M Y H:i') }}
                    &mdash; {{ \Carbon\Carbon::parse($laporan->waktu_selesai)->format('H:i') }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-1">Dikirim Pada</p>
                <p class="text-base font-semibold text-foreground">{{ \Carbon\Carbon::parse($laporan->dikirim_pada)->translatedFormat('d M Y H:i') }}</p>
            </div>
        </div>

        {{-- Uraian Kegiatan --}}
        <div>
            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-2">Uraian Kegiatan</p>
            <div class="p-4 bg-white/40 rounded-xl border border-border/40 text-sm text-foreground leading-relaxed whitespace-pre-wrap">{{ $laporan->uraian_kegiatan }}</div>
        </div>

        {{-- Peserta Hadir --}}
        <div>
            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-2">
                Peserta yang Bertugas
                <span class="ml-2 text-primary font-bold">({{ $pesertaHadir->count() }} orang)</span>
            </p>
            @if($pesertaHadir->isEmpty())
                <p class="text-sm text-muted-foreground italic">Tidak ada peserta yang dicatat bertugas.</p>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($pesertaHadir as $p)
                <div class="flex items-center gap-2 p-2 bg-white/50 rounded-lg">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center shrink-0">
                        <span class="text-xs font-bold text-primary">{{ substr($p->name, 0, 1) }}</span>
                    </div>
                    <span class="text-sm font-medium text-foreground truncate">{{ $p->name }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Dokumen Pendukung --}}
        @if(!empty($laporan->dokumen_pendukung))
        <div>
            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide mb-2">Dokumen Pendukung / Foto</p>
            <div class="space-y-4">
                @foreach($laporan->dokumen_pendukung as $dok)
                    @php
                        $isImage = isset($dok['tipe']) && str_starts_with($dok['tipe'], 'image/');
                    @endphp
                    @if($isImage)
                        <div class="space-y-2 bg-white/40 p-3 rounded-2xl border border-border/40">
                            <div class="flex items-center justify-between text-xs text-muted-foreground">
                                <span class="font-semibold truncate">{{ $dok['nama'] }}</span>
                                <span>{{ round($dok['ukuran'] / 1024, 1) }} KB</span>
                            </div>
                            <div class="rounded-xl overflow-hidden max-w-lg border border-border/40">
                                <a href="{{ Storage::url($dok['path']) }}" target="_blank" title="Klik untuk memperbesar">
                                    <img src="{{ Storage::url($dok['path']) }}" alt="{{ $dok['nama'] }}" class="w-full h-auto object-cover max-h-96 hover:opacity-95 transition-opacity">
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ Storage::url($dok['path']) }}" target="_blank"
                           class="flex items-center gap-3 p-3 bg-white/60 rounded-xl border border-border/50 hover:bg-white hover:border-primary/30 transition-all group">
                            <div class="w-9 h-9 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-foreground truncate group-hover:text-primary transition-colors">{{ $dok['nama'] }}</p>
                                <p class="text-xs text-muted-foreground">{{ round($dok['ukuran'] / 1024, 1) }} KB</p>
                            </div>
                            <svg class="w-4 h-4 text-muted-foreground group-hover:text-primary transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Tombol Keputusan --}}
    @if(
        (Auth::user()->role === 'pengurus_wilayah' && $laporan->status === 'menunggu_wilayah') ||
        (Auth::user()->role === 'pengurus_inti' && $laporan->status === 'menunggu_inti')
    )
    <div class="glass-card p-6 rounded-2xl border border-white/60 space-y-4">
        <h2 class="text-lg font-bold text-foreground">Berikan Keputusan</h2>

        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Tombol Setuju --}}
            <form action="{{ route('persetujuan.approve', $laporan->id) }}" method="POST" class="flex-1">
                @csrf
                @php
                    $confirmMsg = Auth::user()->role === 'pengurus_wilayah'
                        ? 'Setujui laporan ini? Laporan akan diteruskan ke Pengurus Inti.'
                        : 'Setujui laporan ini? Laporan akan ditandai sebagai Disetujui Final.';
                @endphp
                <button type="submit"
                    onclick="return confirm('{{ $confirmMsg }}')"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 shadow-md shadow-emerald-500/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if(Auth::user()->role === 'pengurus_wilayah')
                        Setujui & Teruskan ke Pengurus Inti
                    @else
                        Setujui Final
                    @endif
                </button>
            </form>

            {{-- Tombol Kembalikan (Modal) --}}
            <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-4 bg-red-50 border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Kembalikan untuk Direvisi
            </button>
        </div>
    </div>
    @else
    <div class="glass-card p-6 rounded-2xl border border-white/60 space-y-4">
        <h2 class="text-lg font-bold text-foreground">Status Persetujuan</h2>
        <div class="p-4 rounded-xl text-sm font-semibold flex items-center gap-3 
            @if($laporan->status === 'disetujui')
                bg-emerald-50 border border-emerald-200 text-emerald-700
            @elseif($laporan->status === 'menunggu_inti')
                bg-blue-50 border border-blue-200 text-blue-700
            @elseif($laporan->status === 'menunggu_wilayah')
                bg-yellow-50 border border-yellow-200 text-yellow-700
            @else
                bg-red-50 border border-red-200 text-red-700
            @endif">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                @if($laporan->status === 'disetujui')
                    Laporan ini telah disetujui secara final.
                @elseif($laporan->status === 'menunggu_inti')
                    Laporan telah disetujui oleh Pengurus Wilayah dan sedang menunggu persetujuan final dari Pengurus Inti.
                @elseif($laporan->status === 'menunggu_wilayah')
                    Laporan sedang menunggu persetujuan dari Pengurus Wilayah.
                @elseif($laporan->status === 'dikembalikan_wilayah')
                    Laporan telah dikembalikan oleh Pengurus Wilayah untuk direvisi.
                @elseif($laporan->status === 'dikembalikan_inti')
                    Laporan telah dikembalikan oleh Pengurus Inti untuk direvisi.
                @else
                    Status: {{ ucfirst($laporan->status) }}
                @endif
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Modal Penolakan --}}
<div id="reject-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="glass-card rounded-2xl w-full max-w-lg p-8 shadow-2xl border border-white/60 animate-in fade-in zoom-in duration-300">
        <h3 class="text-xl font-extrabold text-foreground mb-2">Kembalikan Laporan</h3>
        <p class="text-sm text-muted-foreground mb-6">Berikan catatan yang jelas agar Amir tahu apa yang perlu diperbaiki.</p>

        <form action="{{ route('persetujuan.reject', $laporan->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="catatan" class="block text-sm font-bold text-foreground/80 mb-2">Catatan Revisi <span class="text-red-500">*</span></label>
                <textarea id="catatan" name="catatan" rows="4" required
                    placeholder="Tuliskan apa yang perlu diperbaiki atau dilengkapi..."
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-red-400 focus:ring-2 focus:ring-red-200 outline-none transition-all text-foreground placeholder:text-muted-foreground/50 resize-none"></textarea>
                @error('catatan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                    class="px-5 py-2.5 bg-white border border-border text-sm font-semibold text-foreground rounded-xl hover:bg-gray-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white text-sm font-bold rounded-xl hover:bg-red-600 shadow-sm shadow-red-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Catatan Revisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
