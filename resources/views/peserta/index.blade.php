@extends('layouts.app')

@section('title', 'Daftarkan Peserta I\'tikaf - MARKAZ')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Daftarkan Peserta I'tikaf</h1>
            <p class="text-gray-600 mt-1">Pilih jadwal I'tikaf di bawah ini untuk mendaftarkan anggota dari Mahallah Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jadwals as $jadwal)
                <div class="bg-white p-6 rounded border border-gray-200 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                    <div class="mb-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-[#26a35a]/10 text-[#26a35a] mb-2 inline-block">Aktif</span>
                        <h3 class="text-xl font-bold text-gray-900">{{ $jadwal->nama_kegiatan }}</h3>
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $jadwal->mahallah->nama_mahallah ?? 'Lokasi Belum Ditentukan' }}
                        </p>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-6 flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</span>
                        </div>
                        <p class="line-clamp-2 mt-2">{{ $jadwal->deskripsi }}</p>
                    </div>

                    <a href="/peserta/{{ $jadwal->id }}/daftar" class="block w-full text-center bg-[#26a35a]/10 text-[#26a35a] hover:bg-[#26a35a] hover:text-white border border-[#26a35a]/20 font-medium py-2 px-4 rounded transition-colors">
                        Pilih & Daftarkan Anggota
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 text-center border border-gray-200 rounded">
                    <p class="text-gray-500">Tidak ada jadwal I'tikaf yang aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
