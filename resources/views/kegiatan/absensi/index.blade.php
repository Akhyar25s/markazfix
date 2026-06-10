@extends('layouts.app')

@section('title', 'Progress Kegiatan Individual - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Kegiatan Individual</h1>
            <p class="text-sm text-muted-foreground mt-0.5">Pantau capaian target dan riwayat kegiatan harian Anda.</p>
        </div>
        <a href="{{ route('absensi-kegiatan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
            Rekam Kegiatan Baru
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

    {{-- Progress Target --}}
    <div>
        <h2 class="text-lg font-bold text-foreground mb-4">Progress Target Anda</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($progress as $item)
            <div class="glass-card p-6 rounded-2xl border border-white/60 hover:shadow-lg hover:shadow-primary/5 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-foreground">{{ $item['target']->jenisKegiatan->nama_kegiatan ?? 'Kegiatan' }}</h3>
                        <p class="text-xs text-muted-foreground capitalize">
                            Target {{ $item['target']->periode }} 
                            @if($item['target']->periode == 'bulanan')
                                (Bulan {{ $item['target']->bulan }})
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs {{ $item['persentase'] >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-primary/10 text-primary' }}">
                        {{ $item['persentase'] }}%
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-muted-foreground">Tercapai: <span class="text-foreground">{{ $item['capaian'] }}</span></span>
                        <span class="text-muted-foreground">Target: <span class="text-foreground">{{ $item['target']->jumlah_target }}</span></span>
                    </div>
                    <div class="w-full bg-muted/50 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-1000 {{ $item['persentase'] >= 100 ? 'bg-emerald-500' : 'bg-primary' }}" 
                             style="width: {{ $item['persentase'] }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full glass-card p-8 rounded-2xl text-center border border-white/60">
                <p class="text-muted-foreground">Belum ada target kegiatan yang ditetapkan untuk Anda saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Riwayat --}}
    <div class="mt-8">
        <h2 class="text-lg font-bold text-foreground mb-4">Riwayat Terbaru</h2>
        <div class="glass-card rounded-2xl border border-white/60 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-muted/50 text-muted-foreground uppercase text-xs font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Kegiatan</th>
                            <th class="px-6 py-4">Status Absen</th>
                            <th class="px-6 py-4">Validasi Wajah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50 bg-white/20">
                        @forelse($riwayats as $riwayat)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="px-6 py-4 font-semibold text-foreground">
                                {{ \Carbon\Carbon::parse($riwayat->waktu_kegiatan)->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $riwayat->jenisKegiatan->nama_kegiatan ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($riwayat->status_absen === 'berhasil')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200">
                                        Berhasil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 font-bold text-xs border border-red-200">
                                        Gagal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($riwayat->status_wajah === 'dikenali')
                                    <span class="text-emerald-600 font-semibold text-xs">✅ Dikenali</span>
                                @else
                                    <span class="text-red-600 font-semibold text-xs">❌ Tidak Dikenali</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-muted-foreground">
                                Belum ada riwayat kegiatan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
