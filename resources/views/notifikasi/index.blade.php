@extends('layouts.app')

@section('title', 'Notifikasi - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Notifikasi</h1>
            <p class="text-sm text-muted-foreground mt-0.5">Pembaruan dan aktivitas terbaru untuk Anda.</p>
        </div>
        @if($notifikasis->count() > 0 && $notifikasis->where('dibaca', false)->count() > 0)
        <form action="{{ route('notifikasi.tandai-semua') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 hover:bg-white text-primary text-sm font-bold rounded-xl border border-primary/20 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- List Notifikasi --}}
    <div class="glass-card rounded-2xl border border-white/60 overflow-hidden shadow-lg shadow-primary/5">
        @forelse($notifikasis as $notif)
            @php
                $bgClass = $notif->dibaca ? 'bg-white/20' : 'bg-primary/5';
                $iconColor = match($notif->tipe) {
                    'success' => 'text-emerald-500 bg-emerald-100',
                    'warning' => 'text-amber-500 bg-amber-100',
                    'error'   => 'text-red-500 bg-red-100',
                    default   => 'text-primary bg-primary/10',
                };
            @endphp
            <div class="p-5 border-b border-border/50 {{ $bgClass }} hover:bg-white/40 transition-colors flex gap-4 relative group">
                {{-- Indikator belum dibaca --}}
                @if(!$notif->dibaca)
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-full"></div>
                @endif

                {{-- Icon --}}
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $iconColor }}">
                    @if($notif->tipe === 'success')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($notif->tipe === 'warning')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    @elseif($notif->tipe === 'error')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <div class="flex justify-between items-start gap-2">
                        <h3 class="font-bold text-foreground {{ !$notif->dibaca ? 'text-lg' : '' }}">{{ $notif->judul }}</h3>
                        <span class="text-xs text-muted-foreground whitespace-nowrap">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-foreground/80 mt-1 leading-relaxed">{{ $notif->pesan }}</p>
                    
                    {{-- Action buttons --}}
                    <div class="mt-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <form action="{{ route('notifikasi.hapus', $notif->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto bg-muted rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="font-bold text-foreground text-lg">Tidak ada notifikasi</h3>
                <p class="text-muted-foreground mt-1">Anda sudah membaca semua pemberitahuan terbaru.</p>
            </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    <div class="mt-4">
        {{ $notifikasis->links() }}
    </div>

</div>
@endsection
