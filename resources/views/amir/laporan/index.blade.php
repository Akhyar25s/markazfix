@extends('layouts.app')

@section('title', 'Laporan I\'tikaf Saya - MARKAZ')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Laporan I'tikaf Saya</h1>
            <p class="text-sm text-muted-foreground mt-1">Daftar jadwal i'tikaf di mana Anda ditugaskan sebagai Amir</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- List Jadwal --}}
    @if($jadwals->isEmpty())
    <div class="glass-card p-12 text-center rounded-2xl">
        <div class="w-16 h-16 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h3 class="text-lg font-bold text-foreground mb-2">Anda Belum Ditugaskan sebagai Amir</h3>
        <p class="text-sm text-muted-foreground">Menunggu penugasan dari Pengurus Inti.</p>
    </div>
    @else
    <div class="grid gap-4">
        @foreach($jadwals as $jadwal)
        <div class="glass-card p-6 rounded-2xl hover:shadow-lg hover:shadow-primary/10 transition-all duration-300 border border-white/60">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center text-white shadow-md shadow-primary/20 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-foreground text-lg leading-tight">{{ $jadwal->nama_itikaf }}</h3>
                            <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 mt-3 ml-13">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-secondary/10 text-secondary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $jadwal->nama_lokasi }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-primary/10 text-primary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $jadwal->jumlah_laporan }} Laporan Dibuat
                        </span>
                        @php
                            $statusColor = match($jadwal->status) {
                                'berlangsung' => 'bg-emerald-100 text-emerald-700',
                                'dijadwalkan' => 'bg-blue-100 text-blue-700',
                                'selesai' => 'bg-gray-100 text-gray-600',
                                'dibatalkan' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-full capitalize {{ $statusColor }}">
                            {{ str_replace('_', ' ', $jadwal->status) }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('amir.laporan.show', $jadwal->id) }}"
                   class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
                    Kelola Laporan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
