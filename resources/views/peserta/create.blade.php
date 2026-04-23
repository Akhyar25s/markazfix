@extends('layouts.app')

@section('title', 'Pilih Peserta I\'tikaf - MARKAZ')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div class="bg-white p-6 rounded border border-gray-200 shadow-sm flex-1 flex flex-col md:flex-row md:items-center justify-between gap-4 mr-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $jadwal->nama_kegiatan }}</h1>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $jadwal->mahallah->nama_mahallah ?? 'Lokasi Belum Ditentukan' }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500 block">Kapasitas Maksimal</span>
                    <span class="text-lg font-semibold text-gray-800">{{ $jadwal->kapasitas_maksimal }} Orang</span>
                </div>
            </div>
            <a href="/peserta" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded shadow-sm transition-colors whitespace-nowrap">
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Anggota Wilayah</h2>
                <p class="text-sm text-gray-500">Pilih anggota yang akan didaftarkan ke jadwal I'tikaf ini.</p>
            </div>
            
            <form action="/peserta/{{ $jadwal->id }}/daftar" method="POST">
                @csrf
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Pilih</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($anggotas as $anggota)
                                @php
                                    $isRegistered = in_array($anggota->id, $pesertaTerdaftar);
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors {{ $isRegistered ? 'bg-slate-50 opacity-75' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="pengguna_ids[]" value="{{ $anggota->id }}" class="w-5 h-5 text-[#26a35a] rounded border-gray-300 focus:ring-[#26a35a] cursor-pointer" {{ $isRegistered ? 'disabled checked' : '' }}>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                                {{ substr($anggota->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $anggota->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $anggota->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $anggota->no_telepon }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($isRegistered)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">Sudah Terdaftar</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#26a35a]/10 text-[#26a35a]">Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada anggota yang ditemukan di wilayah Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                    <button type="submit" class="px-6 py-2 bg-[#26a35a] hover:bg-[#1e8449] text-white font-medium rounded shadow-sm transition-colors">
                        Daftarkan Peserta Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
