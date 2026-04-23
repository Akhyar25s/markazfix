@extends('layouts.app')

@section('title', 'Buat Jadwal I\'tikaf - MARKAZ')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Jadwal Baru</h1>
                <p class="text-gray-600 mt-1">Isi formulir di bawah ini untuk membuat jadwal kegiatan I'tikaf baru.</p>
            </div>
            <a href="/jadwal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded shadow-sm transition-colors">
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

        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden p-8">
            <form action="/jadwal" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Kegiatan -->
                <div>
                    <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan *</label>
                    <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required placeholder="Contoh: I'tikaf Ramadhan 1447 H - Gelombang 1" class="w-full px-4 py-3 bg-slate-50 border border-gray-200 focus:bg-white focus:border-[#26a35a] focus:ring-1 focus:ring-[#26a35a] rounded outline-none transition-colors text-gray-800 placeholder-gray-400">
                </div>

                <!-- Target Mahallah -->
                <div>
                    <label for="target_mahallah_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Mahallah (Masjid) *</label>
                    <select id="target_mahallah_id" name="target_mahallah_id" required class="w-full px-4 py-3 bg-slate-50 border border-gray-200 focus:bg-white focus:border-[#26a35a] focus:ring-1 focus:ring-[#26a35a] rounded outline-none transition-colors text-gray-800">
                        <option value="">-- Pilih Masjid --</option>
                        @foreach($mahallahs as $mahallah)
                            <option value="{{ $mahallah->id }}" {{ old('target_mahallah_id') == $mahallah->id ? 'selected' : '' }}>
                                {{ $mahallah->nama_mahallah }} ({{ $mahallah->wilayah->nama_wilayah ?? 'Tidak ada wilayah' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Waktu -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                        <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full px-4 py-3 bg-slate-50 border border-gray-200 focus:bg-white focus:border-[#26a35a] focus:ring-1 focus:ring-[#26a35a] rounded outline-none transition-colors text-gray-800">
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai *</label>
                        <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full px-4 py-3 bg-slate-50 border border-gray-200 focus:bg-white focus:border-[#26a35a] focus:ring-1 focus:ring-[#26a35a] rounded outline-none transition-colors text-gray-800">
                    </div>
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas_maksimal" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Maksimal (Orang) *</label>
                    <input type="number" id="kapasitas_maksimal" name="kapasitas_maksimal" value="{{ old('kapasitas_maksimal', 50) }}" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-gray-200 focus:bg-white focus:border-[#26a35a] focus:ring-1 focus:ring-[#26a35a] rounded outline-none transition-colors text-gray-800">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tambahan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Keterangan tambahan terkait kegiatan ini..." class="w-full px-4 py-3 bg-slate-50 border border-gray-200 focus:bg-white focus:border-[#26a35a] focus:ring-1 focus:ring-[#26a35a] rounded outline-none transition-colors text-gray-800 placeholder-gray-400">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-[#26a35a] hover:bg-[#1e8449] text-white font-medium py-3 px-8 rounded shadow-sm transition-colors">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
