<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Dashboard Pengurus Inti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Alert -->
            <x-alert variant="default" title="Selamat Datang!">
                Sistem Informasi Manajemen Organisasi MARKAZ telah diperbarui dengan antarmuka yang modern.
            </x-alert>

            <!-- Stats Row -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <x-card title="Total Anggota" description="Seluruh wilayah aktif">
                    <div class="text-2xl font-bold">1,245</div>
                    <p class="text-xs text-muted-foreground mt-1">+12% dari bulan lalu</p>
                </x-card>
                <x-card title="Jadwal I'tikaf" description="Bulan ini">
                    <div class="text-2xl font-bold">4</div>
                    <p class="text-xs text-muted-foreground mt-1">2 Selesai, 2 Terjadwal</p>
                </x-card>
                <x-card title="Laporan Menunggu" description="Butuh persetujuan">
                    <div class="text-2xl font-bold text-primary">12</div>
                    <p class="text-xs text-muted-foreground mt-1">Dari 3 wilayah berbeda</p>
                </x-card>
                <x-card title="Akurasi Face Rec." description="Rata-rata sistem">
                    <div class="text-2xl font-bold text-green-500">98.5%</div>
                    <p class="text-xs text-muted-foreground mt-1">+0.5% dari bulan lalu</p>
                </x-card>
            </div>

            <!-- Main Content Area -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <!-- Data Table -->
                <x-card class="col-span-4" title="Laporan Terbaru" description="Laporan sesi i'tikaf dari Amir.">
                    <x-table :headers="['Nama Sesi', 'Amir', 'Tanggal', 'Status', 'Aksi']">
                        <tr>
                            <td class="p-4 align-middle">Mabit Ramadhan</td>
                            <td class="p-4 align-middle">Ahmad Fulan</td>
                            <td class="p-4 align-middle">24 Apr 2026</td>
                            <td class="p-4 align-middle"><x-badge variant="warning">Menunggu</x-badge></td>
                            <td class="p-4 align-middle"><x-button variant="outline" size="sm">Review</x-button></td>
                        </tr>
                        <tr>
                            <td class="p-4 align-middle">I'tikaf Akhir Bulan</td>
                            <td class="p-4 align-middle">Budi Santoso</td>
                            <td class="p-4 align-middle">20 Apr 2026</td>
                            <td class="p-4 align-middle"><x-badge variant="success">Disetujui</x-badge></td>
                            <td class="p-4 align-middle"><x-button variant="ghost" size="sm">Detail</x-button></td>
                        </tr>
                        <tr>
                            <td class="p-4 align-middle">Kajian Subuh</td>
                            <td class="p-4 align-middle">Siti Aisyah</td>
                            <td class="p-4 align-middle">18 Apr 2026</td>
                            <td class="p-4 align-middle"><x-badge variant="destructive">Dikembalikan</x-badge></td>
                            <td class="p-4 align-middle"><x-button variant="ghost" size="sm">Detail</x-button></td>
                        </tr>
                    </x-table>
                </x-card>

                <!-- Form Example -->
                <x-card class="col-span-3" title="Buat Jadwal Cepat" description="Tambahkan jadwal i'tikaf baru.">
                    <form class="space-y-4">
                        <div class="space-y-2">
                            <x-label for="nama_itikaf">Nama I'tikaf</x-label>
                            <x-input id="nama_itikaf" placeholder="Masukkan nama i'tikaf..." />
                        </div>
                        <div class="space-y-2">
                            <x-label for="lokasi">Lokasi / Mahallah</x-label>
                            <x-select id="lokasi">
                                <option>Masjid Al-Akbar</option>
                                <option>Masjid Jami'</option>
                            </x-select>
                        </div>
                        <div class="space-y-2">
                            <x-label for="keterangan">Keterangan Tambahan</x-label>
                            <x-textarea id="keterangan" placeholder="Target jamaah, pemateri, dll..."></x-textarea>
                        </div>
                        <x-button class="w-full">Simpan Jadwal</x-button>
                    </form>
                </x-card>
            </div>

        </div>
    </div>
</x-app-layout>
