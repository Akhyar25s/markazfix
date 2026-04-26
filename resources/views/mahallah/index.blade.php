@extends('layouts.app')

@section('title', 'Manajemen Mahallah')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Manajemen Mahallah</h1>
            <p class="text-muted-foreground mt-1 text-sm sm:text-base">Kelola data mahallah untuk Sistem I'tikaf Markaz.</p>
        </div>
        <a href="{{ route('mahallah.create') }}">
            <x-button variant="default" class="flex items-center gap-2 shadow-primary/30 shadow-lg hover:shadow-primary/50 hover:scale-105 transition-all">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Mahallah
            </x-button>
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif

    <x-card class="overflow-hidden border-primary/10 shadow-xl relative backdrop-blur-md bg-card/80">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 z-0 pointer-events-none"></div>
        <div class="relative z-10">
            <x-table :headers="['No', 'Nama Mahallah', 'Wilayah', 'Status', 'Aksi']">
                @forelse($mahallahs as $mahallah)
                    <tr class="border-b border-border transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-4 align-middle text-muted-foreground">{{ $loop->iteration + $mahallahs->firstItem() - 1 }}</td>
                        <td class="p-4 align-middle font-medium text-foreground">
                            {{ $mahallah->nama_mahallah }}
                            @if($mahallah->alamat)
                                <span class="block text-xs text-muted-foreground font-normal mt-0.5 line-clamp-1">{{ $mahallah->alamat }}</span>
                            @endif
                        </td>
                        <td class="p-4 align-middle text-muted-foreground">
                            @if($mahallah->wilayah)
                                <a href="{{ route('wilayah.show', $mahallah->wilayah->id) }}" class="hover:underline hover:text-primary transition-colors">
                                    {{ $mahallah->wilayah->nama_wilayah }}
                                </a>
                            @else
                                <span class="text-xs italic text-muted-foreground/60">-</span>
                            @endif
                        </td>
                        <td class="p-4 align-middle">
                            @if($mahallah->status == 'aktif')
                                <x-badge variant="success" class="bg-green-500/10 text-green-500 border-green-500/20">Aktif</x-badge>
                            @else
                                <x-badge variant="danger" class="bg-red-500/10 text-red-500 border-red-500/20">Nonaktif</x-badge>
                            @endif
                        </td>
                        <td class="p-4 align-middle">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('mahallah.show', $mahallah->id) }}" class="p-2 text-blue-500 hover:bg-blue-500/10 rounded-md transition-colors" title="Detail">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('mahallah.edit', $mahallah->id) }}" class="p-2 text-accent hover:bg-accent/10 rounded-md transition-colors" title="Edit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('mahallah.destroy', $mahallah->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahallah ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-500/10 rounded-md transition-colors" title="Hapus">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-muted-foreground">
                            <div class="flex flex-col items-center justify-center">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-muted-foreground/50 mb-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <p>Belum ada data mahallah</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>
        
        @if($mahallahs->hasPages())
            <div class="p-4 border-t border-border">
                {{ $mahallahs->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
