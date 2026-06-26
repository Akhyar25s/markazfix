# Prompt: Permintaan Pengembangan Fitur Sistem Informasi MARKAZ

Saya ingin menambahkan dan memodifikasi beberapa fitur pada sistem manajemen itikaf MARKAZ yang sudah ada (berbasis Laravel/PHP + MySQL). Berikut daftar perubahan yang dibutuhkan:

---

## 1. Tambahkan Foto dan Lokasi Maps untuk Tempat Ibadah Islam

Gunakan pendekatan **hybrid**: upload foto manual + embed Google Maps berdasarkan koordinat.

> Catatan: "Tempat ibadah Islam" mencakup semua jenis lokasi itikaf yang ada di sistem (masjid, langgar, mushola, atau jenis lainnya). Jangan hardcode nama jenisnya — sesuaikan dengan struktur data yang sudah ada.

### Foto (Upload Manual)
- Tambahkan field untuk menyimpan **foto/gambar** tempat ibadah Islam di tabel database.
- Sediakan form **upload foto** saat menambah atau mengedit data tempat ibadah Islam.
- Simpan file foto di storage dan simpan path-nya di database.
- Tampilkan foto di halaman daftar dan detail tempat ibadah Islam.

### Lokasi (Google Maps Embed)
- Tambahkan dua field di database: **latitude** dan **longitude** (tipe decimal).
- Di form tambah/edit tempat ibadah Islam, sediakan input **latitude** dan **longitude** (admin bisa ambil koordinat dari Google Maps secara manual).
- Di halaman detail tempat ibadah Islam, tampilkan **embed Google Maps iframe** menggunakan koordinat tersebut, contoh format URL embed:
  ```
  https://maps.google.com/maps?q={latitude},{longitude}&z=16&output=embed
  ```
- Iframe ditampilkan hanya jika koordinat sudah diisi.
- Tidak memerlukan API key Google.

---

## 2. Ganti Tanggal Lahir Menjadi Umur

- Pada form dan tampilan data **anggota**, ganti field **tanggal lahir** menjadi field **umur** (dalam satuan tahun, berupa angka integer).
- Sesuaikan di database (migration), form input, tabel daftar anggota, dan halaman detail anggota.

---

## 3. Tambahkan Tombol Hapus pada Data Anggota Itikaf

- Di halaman daftar **anggota itikaf**, tambahkan tombol atau aksi **Hapus** untuk setiap baris data.
- Tambahkan konfirmasi sebelum menghapus (dialog: "Apakah Anda yakin ingin menghapus anggota ini?").
- Setelah dihapus, data anggota terhapus dari database dan halaman diperbarui.

---

## 4. Kegiatan Itikaf Dibuat Per Hari dengan Sesi Tetap

- Ubah struktur **kegiatan itikaf** menjadi berbasis **hari**.
- Setiap hari memiliki daftar kegiatan tetap (sesi) berikut:
  1. Bayan Subuh
  2. Talim Pagi
  3. Talim Zhuhur
  4. Talim Ashar
  5. Bayan Maghrib
  6. Talim Akhir
- Saat admin membuat atau mengelola kegiatan untuk suatu hari, keenam sesi tersebut sudah tersedia sebagai pilihan atau otomatis terbuat.
- Tampilkan kegiatan per hari secara terstruktur (dikelompokkan berdasarkan tanggal/hari ke-berapa).

---

## 5. Opsi "Lainnya" pada Asal Daerah di Form Pendaftaran Anggota

- Pada form **pendaftaran anggota**, di field **asal daerah/wilayah**, tambahkan opsi **"Lainnya"**.
- Jika pengguna memilih "Lainnya", tampilkan input teks bebas agar pengguna bisa mengisi asal daerahnya secara manual (contoh: "Yaman", "Kalimantan Timur", dll).
- Ini untuk mengakomodasi anggota dari luar daerah yang tidak masuk ke dalam pilihan wilayah yang sudah ada.
- Field **jenis kelamin dihapus** dari form pendaftaran dan tampilan data anggota karena seluruh peserta itikaf adalah laki-laki. Sesuaikan juga di database (hapus kolom atau set default tetap ke "laki-laki").

---

## 6. Fitur Pendaftaran Tamu / Walk-in (Anggota Luar Tanpa Akun)

Ini fitur baru untuk mengakomodasi peserta itikaf dari luar daerah atau luar negeri yang datang tanpa akun dan tidak terdaftar di wilayah manapun (contoh: jamaah dari Yaman, atau tamu dari daerah lain).

### Latar Belakang
Sistem saat ini mengharuskan anggota registrasi mandiri terlebih dahulu sebelum bisa mengikuti itikaf. Namun ada kasus di mana seseorang datang langsung tanpa akun. Karena sistem menggunakan **face recognition** untuk absensi dan **GPS geofencing** untuk tracking kehadiran, orang tersebut harus terdaftar di sistem agar bisa ditracking.

### Solusi: Alur Pendaftaran Tamu oleh Amir/Pengurus

1. **Amir I'tikaf atau Pengurus** dapat mendaftarkan tamu secara langsung (on-the-spot) melalui web atau aplikasi.
2. Data yang diinput saat pendaftaran tamu:
   - Nama lengkap
   - Asal daerah (input teks bebas, wajib diisi)
   - Foto wajah (diambil langsung saat itu menggunakan kamera perangkat, untuk keperluan face enrollment)
3. Akun langsung aktif dengan status **"Tamu"** (bukan anggota resmi).
4. Tamu ditempatkan di bawah **wilayah "Tamu/Lainnya"** yang merupakan wilayah default penampung — buat wilayah ini jika belum ada.
5. Setelah face enrollment selesai, tamu langsung bisa melakukan absensi menggunakan face recognition dan tertracking via GPS seperti anggota biasa.
6. Data tamu tetap tersimpan setelah itikaf selesai untuk keperluan rekap/laporan.
7. Pengurus Inti dapat mengkonversi akun tamu menjadi anggota resmi jika diperlukan di kemudian hari.

### Yang Perlu Disesuaikan
- Tambahkan kolom `status` di tabel pengguna/anggota dengan nilai: `aktif`, `tamu`, `nonaktif`.
- Tambahkan wilayah default bernama "Tamu" atau "Lainnya" via seeder jika belum ada.
- Tambahkan halaman/form khusus pendaftaran tamu yang dapat diakses oleh Amir dan Pengurus.
- Di dashboard dan laporan, pisahkan atau beri label anggota berstatus "Tamu" agar mudah diidentifikasi.

---

Tolong implementasikan semua perubahan di atas sesuai dengan struktur proyek yang sudah ada (Laravel/PHP + MySQL). Sesuaikan migration database, model, controller, dan view yang terkait.
