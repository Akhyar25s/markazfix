@extends('layouts.app')

@section('title', 'Daftar Tempat Ibadah - Markaz')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-foreground">Daftar Tempat Ibadah</h2>
            @if(auth()->user()->role === 'pengurus_inti')
            <a href="{{ route('tempat-ibadah.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Tempat Ibadah
            </a>
            @endif
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 text-muted-foreground border-b border-border">
                        <tr>
                            <th class="text-left p-4 font-medium">No</th>
                            <th class="text-left p-4 font-medium">Nama</th>
                            <th class="text-left p-4 font-medium">Jenis</th>
                            <th class="text-left p-4 font-medium">Mahallah</th>
                            <th class="text-left p-4 font-medium">Koordinat</th>
                            <th class="text-left p-4 font-medium">Radius</th>
                            <th class="text-right p-4 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($tempatIbadahs as $index => $tb)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-medium text-foreground">{{ $tb->nama }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $tb->jenis === 'masjid' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $tb->jenis === 'langgar' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $tb->jenis === 'mushola' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $tb->jenis === 'lainnya' ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    {{ ucfirst($tb->jenis) }}
                                </span>
                            </td>
                            <td class="p-4 text-muted-foreground">{{ $tb->mahallah->nama ?? '-' }}</td>
                            <td class="p-4 text-muted-foreground text-xs">{{ $tb->latitude }}, {{ $tb->longitude }}</td>
                            <td class="p-4 text-muted-foreground">{{ $tb->radius_meter }}m</td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tempat-ibadah.show', $tb->id) }}" class="text-primary hover:underline text-sm">Detail</a>
                                    @if(auth()->user()->role === 'pengurus_inti')
                                    <a href="{{ route('tempat-ibadah.edit', $tb->id) }}" class="text-amber-600 hover:underline text-sm">Edit</a>
                                    <form action="{{ route('tempat-ibadah.destroy', $tb->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tempat ibadah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-muted-foreground">Belum ada data tempat ibadah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
