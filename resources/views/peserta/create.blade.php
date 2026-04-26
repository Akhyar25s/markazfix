@extends('layouts.app')

@section('title', 'Daftarkan Peserta')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ route('peserta.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Daftarkan Peserta</h1>
            <p class="text-muted-foreground mt-1 text-sm">Pilih anggota wilayah Anda untuk mengikuti I'tikaf.</p>
        </div>
    </div>

    @if (session('error'))
        <x-alert type="danger" message="{{ session('error') }}" />
    @endif
    
    @if ($errors->any())
        <x-alert type="danger" message="Silakan pilih setidaknya satu peserta untuk didaftarkan." />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 space-y-6">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg sticky top-6">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold tracking-tight text-primary">Informasi Jadwal</h3>
                </x-slot>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-muted-foreground mb-1 uppercase tracking-wider font-semibold">Kegiatan</div>
                        <div class="font-medium text-foreground">{{ $jadwal->nama_itikaf }}</div>
                    </div>
                    
                    <div>
                        <div class="text-xs text-muted-foreground mb-1 uppercase tracking-wider font-semibold">Pelaksanaan</div>
                        <div class="text-sm">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }} s/d <br>
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-muted-foreground mb-1 uppercase tracking-wider font-semibold">Lokasi</div>
                        <div class="text-sm font-medium">{{ $jadwal->nama_lokasi ?? 'Tidak diketahui' }}</div>
                    </div>
                    
                    <div class="pt-4 border-t border-border mt-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-muted-foreground">Radius Maksimal</span>
                            <span class="font-bold">{{ $jadwal->radius_meter }} m</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-muted-foreground">Sudah Terdaftar</span>
                            <span class="font-bold text-accent">{{ count($pesertaTerdaftar) }}</span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="md:col-span-2">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold tracking-tight">Pilih Anggota Wilayah</h3>
                    <div class="text-xs bg-muted text-muted-foreground px-2 py-1 rounded-md">
                        Total: {{ count($anggotas) }} Anggota
                    </div>
                </div>

                <form action="{{ route('peserta.store', $jadwal->id) }}" method="POST">
                    @csrf

                    <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($anggotas as $anggota)
                            @php
                                $isRegistered = in_array($anggota->id, $pesertaTerdaftar);
                            @endphp
                            
                            <label class="flex items-center p-4 border rounded-xl transition-all cursor-pointer {{ $isRegistered ? 'bg-muted/50 border-border opacity-70 cursor-not-allowed' : 'border-border/60 hover:border-primary/40 hover:bg-primary/5' }}">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" name="pengguna_ids[]" value="{{ $anggota->id }}" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-primary/50 transition-all checked:border-primary checked:bg-primary checked:before:bg-primary hover:before:opacity-10" {{ $isRegistered ? 'disabled checked' : '' }}>
                                    <span class="absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="font-medium text-foreground">{{ $anggota->name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $anggota->email }}</p>
                                </div>
                                
                                @if($isRegistered)
                                    <x-badge class="bg-green-500/10 text-green-500 border-green-500/20 text-xs">Sudah Terdaftar</x-badge>
                                @endif
                            </label>
                        @empty
                            <div class="py-8 text-center flex flex-col items-center">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 text-muted-foreground/40 mb-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="text-muted-foreground font-medium">Belum ada anggota di wilayah Anda.</p>
                                <p class="text-xs text-muted-foreground mt-1">Pastikan ada pengguna dengan role anggota yang ditugaskan ke wilayah Anda.</p>
                            </div>
                        @endforelse
                    </div>

                    @if(count($anggotas) > 0)
                        <div class="flex justify-end pt-6 border-t border-border mt-6">
                            <x-button type="submit" variant="default" class="shadow-primary/20 shadow-lg hover:shadow-primary/40 px-8">
                                Daftarkan Terpilih
                            </x-button>
                        </div>
                    @endif
                </form>
            </x-card>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for the members list */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(var(--primary), 0.2); 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(var(--primary), 0.4); 
    }
</style>
@endsection
