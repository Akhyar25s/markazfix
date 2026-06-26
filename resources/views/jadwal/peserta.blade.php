@extends('layouts.app')

@section('title', 'Peserta & Amir - ' . $jadwal->nama_itikaf)

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('jadwal.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Peserta & Penugasan Amir</h1>
                <p class="text-sm text-muted-foreground mt-0.5">{{ $jadwal->nama_itikaf }} &middot; {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('d M Y') }}</p>
            </div>
        </div>
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

    {{-- Content --}}
    <div class="glass-card rounded-2xl border border-white/60 overflow-hidden shadow-lg shadow-primary/5">
        
        <div class="p-6 border-b border-border/50 bg-white/40 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-foreground">Daftar Peserta Terdaftar</h2>
                <p class="text-sm text-muted-foreground">Pilih salah satu peserta untuk ditugaskan sebagai Amir (Pemimpin I'tikaf).</p>
            </div>
            <div class="text-sm font-bold bg-primary/10 text-primary px-4 py-2 rounded-xl border border-primary/20">
                Total: {{ $jadwal->pesertas->count() }} Peserta
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-muted/50 text-muted-foreground uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">Wilayah</th>
                        <th class="px-6 py-4 text-center">Status / Jabatan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50 bg-white/20">
                    @forelse($jadwal->pesertas as $peserta)
                    <tr class="hover:bg-white/40 transition-colors {{ $peserta->adalah_amir ? 'bg-amber-50/50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-bold text-primary">{{ substr($peserta->pengguna->name ?? '?', 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-bold text-foreground">{{ $peserta->pengguna->name ?? 'Data Terhapus' }}</div>
                                    @if($peserta->pengguna && $peserta->pengguna->status === 'tamu')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-semibold text-[10px] border border-blue-200 mt-0.5">🌍 Tamu</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-muted-foreground">
                            {{ $peserta->pengguna->wilayah->nama_wilayah ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($peserta->adalah_amir)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-bold text-xs border border-amber-200 shadow-sm shadow-amber-500/10">
                                    <span>👑</span> Amir I'tikaf
                                </div>
                                <div class="text-[10px] text-muted-foreground mt-1">Ditunjuk oleh: {{ $peserta->pemilih->name ?? '-' }}</div>
                            @else
                                <span class="text-muted-foreground">Peserta Biasa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$peserta->adalah_amir)
                                <form action="{{ route('jadwal.jadikan-amir', ['id' => $jadwal->id, 'peserta_id' => $peserta->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Tunjuk {{ $peserta->pengguna->name }} sebagai Amir I\'tikaf? Amir sebelumnya (jika ada) akan kembali menjadi peserta biasa.')"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-border text-xs font-bold text-foreground rounded-xl hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                                        <span>👑</span> Jadikan Amir
                                    </button>
                                </form>
                                @else
                                <button disabled class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 border border-gray-200 text-xs font-bold text-gray-400 rounded-xl cursor-not-allowed">
                                    Sedang Menjabat
                                </button>
                                @endif

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('jadwal.hapus-peserta', ['id' => $jadwal->id, 'peserta_id' => $peserta->id]) }}" method="POST"
                                      onsubmit="return confirm('Hapus {{ addslashes($peserta->pengguna->name ?? "peserta ini") }} dari daftar? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 border border-red-200 text-xs font-bold text-red-600 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto bg-muted rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <h3 class="font-bold text-foreground">Belum Ada Peserta Terdaftar</h3>
                            <p class="text-sm text-muted-foreground mt-1">Pengurus Wilayah belum mendaftarkan peserta untuk jadwal ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
