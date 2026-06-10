@extends('layouts.app')

@section('title', 'Target Kegiatan Individual - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Target Kegiatan Individual</h1>
            <p class="text-sm text-muted-foreground mt-0.5">Kelola kuota atau target capaian kegiatan anggota.</p>
        </div>
        <a href="{{ route('target-kegiatan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tetapkan Target Baru
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Content --}}
    <div class="glass-card rounded-2xl border border-white/60 overflow-hidden shadow-lg shadow-primary/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-muted/50 text-muted-foreground uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Jenis Kegiatan</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Target Jumlah</th>
                        <th class="px-6 py-4 text-muted-foreground">Ditetapkan Oleh</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50 bg-white/20">
                    @forelse($targetKegiatans as $target)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-foreground">
                            {{ $target->jenisKegiatan->nama_kegiatan ?? 'Kegiatan Dihapus' }}
                        </td>
                        <td class="px-6 py-4 text-muted-foreground">
                            @if($target->periode === 'bulanan')
                                <span class="capitalize text-primary font-semibold">Bulanan</span> 
                                (Bulan {{ $target->bulan }} - {{ $target->tahun }})
                            @else
                                <span class="capitalize text-amber-600 font-semibold">Tahunan</span> 
                                (Tahun {{ $target->tahun }})
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">
                                {{ $target->jumlah_target }} Kali
                            </div>
                        </td>
                        <td class="px-6 py-4 text-muted-foreground text-xs">
                            {{ $target->penetap->name ?? '-' }}<br>
                            {{ \Carbon\Carbon::parse($target->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('target-kegiatan.edit', $target->id) }}" class="inline-flex items-center justify-center p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('target-kegiatan.destroy', $target->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Anda yakin ingin menghapus target kegiatan ini?')" class="inline-flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto bg-muted rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="font-bold text-foreground">Belum Ada Target</h3>
                            <p class="text-sm text-muted-foreground mt-1">Silakan tetapkan target kegiatan untuk anggota.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
