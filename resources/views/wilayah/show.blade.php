@extends('layouts.app')

@section('title', 'Detail Wilayah')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('wilayah.index') }}" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-foreground">Detail Wilayah</h1>
                <p class="text-muted-foreground mt-1 text-sm sm:text-base">Informasi lengkap mengenai {{ $wilayah->nama_wilayah }} dan daftar Mahallah.</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('wilayah.edit', $wilayah->id) }}">
                <x-button variant="outline" class="flex items-center gap-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Wilayah
                </x-button>
            </a>
            <a href="{{ route('mahallah.create', ['wilayah_id' => $wilayah->id]) }}">
                <x-button variant="default" class="flex items-center gap-2 shadow-primary/30 shadow-lg">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Mahallah
                </x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Wilayah -->
        <div class="lg:col-span-1 space-y-6">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>
                
                <x-slot name="header">
                    <h3 class="text-lg font-semibold tracking-tight">Informasi Dasar</h3>
                </x-slot>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Nama Wilayah</div>
                        <div class="font-medium text-lg">{{ $wilayah->nama_wilayah }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Deskripsi</div>
                        <div class="text-sm {{ $wilayah->deskripsi ? 'text-foreground' : 'text-muted-foreground italic' }}">
                            {{ $wilayah->deskripsi ?: 'Tidak ada deskripsi' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-muted-foreground mb-1">Status</div>
                        @if($wilayah->status == 'aktif')
                            <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">Aktif</x-badge>
                        @else
                            <x-badge variant="danger" class="bg-red-500/10 text-red-500 border-red-500/20">Nonaktif</x-badge>
                        @endif
                    </div>
                    
                    <div class="pt-4 border-t border-border">
                        <div class="text-sm text-muted-foreground mb-3">Pengurus Wilayah</div>
                        @if($wilayah->pengurus)
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center text-sm font-bold text-primary">
                                    {{ substr($wilayah->pengurus->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ $wilayah->pengurus->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $wilayah->pengurus->email }}</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-muted-foreground/60 italic text-sm">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Belum ada pengurus yang ditugaskan
                            </div>
                        @endif
                    </div>
                </div>
            </x-card>
            
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold tracking-tight">Statistik</h3>
                </x-slot>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-muted/50 border border-border">
                        <div class="text-sm text-muted-foreground mb-1">Total Mahallah</div>
                        <div class="text-3xl font-bold text-foreground">{{ $wilayah->mahallahs->count() }}</div>
                    </div>
                    <div class="p-4 rounded-xl bg-primary/5 border border-primary/10">
                        <div class="text-sm text-muted-foreground mb-1">Total Peserta</div>
                        <div class="text-3xl font-bold text-primary">{{ $wilayah->users->count() }}</div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Daftar Mahallah -->
        <div class="lg:col-span-2 space-y-6">
            <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-lg h-full">
                <x-slot name="header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold tracking-tight">Daftar Mahallah</h3>
                            <p class="text-sm text-muted-foreground">Mahallah yang berada di bawah naungan wilayah ini.</p>
                        </div>
                    </div>
                </x-slot>
                
                <x-table :headers="['No', 'Nama Mahallah', 'Status', 'Aksi']">
                    @forelse($wilayah->mahallahs as $mahallah)
                        <tr class="border-b border-border transition-colors hover:bg-muted/50">
                            <td class="p-4 align-middle text-muted-foreground">{{ $loop->iteration }}</td>
                            <td class="p-4 align-middle font-medium text-foreground">
                                {{ $mahallah->nama_mahallah }}
                            </td>
                            <td class="p-4 align-middle">
                                @if($mahallah->status == 'aktif')
                                    <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">Aktif</x-badge>
                                @else
                                    <x-badge variant="danger" class="bg-red-500/10 text-red-500 border-red-500/20">Nonaktif</x-badge>
                                @endif
                            </td>
                            <td class="p-4 align-middle">
                                <a href="{{ route('mahallah.show', $mahallah->id) }}" class="p-2 text-blue-500 hover:bg-blue-500/10 rounded-md transition-colors inline-block" title="Detail Mahallah">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-muted-foreground">
                                <div class="flex flex-col items-center justify-center">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 text-muted-foreground/30 mb-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p>Belum ada mahallah di wilayah ini</p>
                                    <a href="{{ route('mahallah.create', ['wilayah_id' => $wilayah->id]) }}" class="mt-2 text-sm text-primary hover:underline">Tambah Mahallah Sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
</div>
@endsection
