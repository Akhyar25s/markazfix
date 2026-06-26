@extends('layouts.app')

@section('title', 'Laporan Sesi - {{ $jadwal->nama_itikaf }} - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('amir.laporan.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Laporan Sesi I'tikaf</h1>
                <p class="text-sm text-muted-foreground mt-0.5">{{ $jadwal->nama_itikaf }} &middot; {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('d M Y') }}</p>
            </div>
        </div>
        <a href="{{ route('amir.laporan.create', $jadwal->id) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan Sesi Baru
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Daftar Laporan --}}
    @if($laporan->isEmpty())
    <div class="glass-card p-12 text-center rounded-2xl">
        <div class="w-16 h-16 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-foreground mb-2">Belum Ada Laporan Sesi</h3>
        <p class="text-sm text-muted-foreground mb-4">Klik tombol "Buat Laporan Sesi Baru" untuk membuat laporan pertama Anda.</p>
    </div>
    @else
    @php
        // Kelompokkan laporan per tanggal
        $laporanPerHari = $laporan->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->waktu_mulai)->format('Y-m-d');
        })->sortKeys();

        $tanggalMulai = \Carbon\Carbon::parse($jadwal->tanggal_mulai);

        $sesiIcon = [
            'Bayan Subuh'   => '🌅',
            'Talim Pagi'    => '☀️',
            'Talim Zhuhur'  => '🕛',
            'Talim Ashar'   => '🕓',
            'Bayan Maghrib' => '🌇',
            'Talim Akhir'   => '🌙',
        ];
    @endphp
    <div class="space-y-8">
        @foreach($laporanPerHari as $tanggal => $items)
        @php
            $hariKe = $tanggalMulai->diffInDays(\Carbon\Carbon::parse($tanggal)) + 1;
            $tglFormatted = \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y');
        @endphp

        {{-- Header Hari --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                    <span class="text-sm font-black text-primary">H{{ $hariKe }}</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-foreground">Hari ke-{{ $hariKe }}</h2>
                    <p class="text-xs text-muted-foreground">{{ $tglFormatted }}</p>
                </div>
                <div class="flex-1 h-px bg-border/60 ml-2"></div>
                <span class="text-xs font-semibold bg-primary/10 text-primary px-3 py-1 rounded-full">{{ $items->count() }} sesi</span>
            </div>

            <div class="space-y-3 pl-4 border-l-2 border-primary/20 ml-5">
                @foreach($items->sortBy('waktu_mulai') as $item)
                @php
                    $statusConfig = match($item->status) {
                        'draft'                => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-600'],
                        'menunggu_wilayah'     => ['label' => 'Menunggu Wilayah', 'class' => 'bg-yellow-100 text-yellow-700'],
                        'dikembalikan_wilayah' => ['label' => 'Dikembalikan (Revisi)', 'class' => 'bg-orange-100 text-orange-700'],
                        'menunggu_inti'        => ['label' => 'Menunggu Pengurus Inti', 'class' => 'bg-blue-100 text-blue-700'],
                        'dikembalikan_inti'    => ['label' => 'Dikembalikan (Inti)', 'class' => 'bg-orange-100 text-orange-700'],
                        'disetujui'            => ['label' => 'Disetujui ✓', 'class' => 'bg-emerald-100 text-emerald-700'],
                        default                => ['label' => $item->status, 'class' => 'bg-gray-100 text-gray-600'],
                    };
                    $icon = $sesiIcon[$item->nama_sesi] ?? '📋';
                @endphp
                <div class="glass-card p-5 rounded-2xl border border-white/60 transition-all hover:shadow-md">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="text-lg">{{ $icon }}</span>
                                <h3 class="font-bold text-foreground">{{ $item->nama_sesi }}</h3>
                                <span class="inline-flex items-center text-xs font-bold px-2.5 py-0.5 rounded-full {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-foreground mb-2">
                                {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} &mdash; {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                            </p>
                            <p class="text-sm text-foreground/80 line-clamp-2">{{ $item->uraian_kegiatan }}</p>

                            @if($item->status === 'dikembalikan_wilayah' && $item->catatan_wilayah)
                            <div class="mt-2 p-2.5 bg-orange-50 border border-orange-200 rounded-xl text-xs text-orange-700">
                                <span class="font-bold">Catatan Wilayah:</span> {{ $item->catatan_wilayah }}
                            </div>
                            @endif
                            @if($item->status === 'dikembalikan_inti' && $item->catatan_inti)
                            <div class="mt-2 p-2.5 bg-orange-50 border border-orange-200 rounded-xl text-xs text-orange-700">
                                <span class="font-bold">Catatan Inti:</span> {{ $item->catatan_inti }}
                            </div>
                            @endif

                            <div class="flex items-center gap-3 mt-2 text-xs text-muted-foreground">
                                <span>{{ count($item->peserta_hadir ?? []) }} hadir</span>
                                <span>&bull;</span>
                                <span>{{ count($item->dokumen_pendukung ?? []) }} dokumen</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if(in_array($item->status, ['draft', 'dikembalikan_wilayah', 'dikembalikan_inti']))
                                <a href="{{ route('amir.laporan.edit', $item->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-border text-xs font-semibold rounded-xl hover:bg-gray-50 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('amir.laporan.kirim', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Kirim laporan ini ke Pengurus Wilayah?')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 shadow-sm transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Kirim
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
