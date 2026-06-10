@extends('layouts.app')

@section('title', 'Persetujuan Laporan - MARKAZ')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-extrabold text-foreground tracking-tight">{{ $title }}</h1>
        <p class="text-sm text-muted-foreground mt-1">
            @if(Auth::user()->role === 'pengurus_wilayah')
                Laporan dari Amir yang perlu Anda tinjau dan setujui sebelum diteruskan ke Pengurus Inti.
            @else
                Laporan yang telah disetujui Pengurus Wilayah dan menunggu persetujuan final Anda.
            @endif
        </p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Daftar Laporan --}}
    @if($laporan->isEmpty())
    <div class="glass-card p-12 text-center rounded-2xl">
        <div class="w-16 h-16 mx-auto bg-emerald-50 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-foreground mb-2">Semua Laporan Sudah Diproses</h3>
        <p class="text-sm text-muted-foreground">Tidak ada laporan yang menunggu persetujuan saat ini.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($laporan as $item)
        <div class="glass-card p-6 rounded-2xl border border-white/60 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700">
                            Menunggu Persetujuan
                        </span>
                        <span class="text-xs text-muted-foreground">
                            Dikirim {{ \Carbon\Carbon::parse($item->dikirim_pada)->diffForHumans() }}
                        </span>
                    </div>
                    <h3 class="font-bold text-foreground text-lg mb-1">{{ $item->nama_sesi }}</h3>
                    <p class="text-sm text-muted-foreground">
                        <span class="font-semibold text-primary">{{ $item->jadwal->nama_itikaf }}</span>
                        &middot; Amir: <span class="font-semibold">{{ $item->amir->name }}</span>
                    </p>
                    <p class="text-sm text-muted-foreground mt-1">
                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y H:i') }}
                        &mdash; {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                    </p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-muted-foreground">
                        <span>{{ count($item->peserta_hadir ?? []) }} peserta hadir</span>
                        <span>&bull;</span>
                        <span>{{ count($item->dokumen_pendukung ?? []) }} dokumen</span>
                    </div>
                </div>
                <a href="{{ route('persetujuan.show', $item->id) }}"
                   class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
                    Tinjau Laporan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
