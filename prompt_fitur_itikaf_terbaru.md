# Prompt: Pengembangan Fitur Terbaru Sistem Informasi MARKAZ

Saya ingin menambahkan fitur-fitur baru berikut pada sistem MARKAZ yang sudah ada (Laravel/PHP + MySQL + Leaflet.js):

---

## 1. Relasi Mahallah → Tempat Ibadah Islam (Fitur Baru)

### Struktur Relasi
- Satu Mahallah dapat memiliki **banyak Tempat Ibadah Islam** (masjid, langgar, mushola, dll).
- Tempat Ibadah Islam wajib terikat ke satu Mahallah.
- Buat tabel baru `tempat_ibadah` dengan kolom:
  - `id`
  - `mahallah_id` (foreign key ke tabel mahallah)
  - `nama`
  - `jenis` (masjid / langgar / mushola / lainnya)
  - `foto` (path file)
  - `latitude` (decimal)
  - `longitude` (decimal)
  - `radius_meter` (integer — untuk geofencing absensi)
  - `timestamps`

### Foto (Upload Manual)
- Sediakan form upload foto saat menambah atau mengedit data tempat ibadah Islam.
- Simpan file foto di storage dan simpan path-nya di database.

### Lokasi (Embed Maps Tanpa API Key)
- Admin input latitude dan longitude secara manual.
- Di halaman detail tempat ibadah, tampilkan embed peta:
  ```
  https://maps.google.com/maps?q={latitude},{longitude}&z=16&output=embed
  ```
- Iframe hanya ditampilkan jika koordinat sudah diisi.

### Tampilan di Halaman Detail Mahallah
- Tambahkan section **"Tempat Ibadah Islam"** di halaman detail Mahallah.
- Tampilkan peta Leaflet kecil khusus mahallah tersebut, berisi marker per tempat ibadah yang terdaftar.
- Klik marker → popup: nama tempat ibadah, jenis, foto.
- Peta utama di dashboard (peta persebaran Mahallah) tidak diubah.

### CRUD Tempat Ibadah
- Pengurus Inti dapat menambah, mengedit, dan menghapus data tempat ibadah Islam.
- Di form tambah/edit, tersedia dropdown untuk memilih Mahallah tempat ibadah ini terdaftar.

---

## 2. Pembaruan Alur Jadwal I'tikaf

### Alur Baru
> Buat Jadwal → pilih Mahallah → **pilih satu Tempat Ibadah Islam** (difilter otomatis berdasarkan Mahallah yang dipilih) → selesai

### Aturan
- Satu jadwal itikaf = satu Mahallah = satu Tempat Ibadah Islam spesifik.
- Tempat ibadah lain di mahallah yang sama tidak ikut serta di jadwal tersebut.
- Setelah jadwal dibuat oleh Pengurus Inti, sistem otomatis **mengirim notifikasi** ke Pengurus Wilayah yang bersangkutan.

### Penugasan Anggota & Amir
- Pengurus Wilayah memilih anggota jamaah dari wilayahnya untuk ditugaskan ke itikaf. Anggota tidak harus dari mahallah yang sama dengan lokasi itikaf.
- Pengurus Inti menunjuk **satu Amir** dari daftar anggota yang sudah ditugaskan ke jadwal itikaf tersebut.
- Satu jadwal itikaf = satu Amir.

---

## 3. GPS Geofencing saat Absensi

- GPS hanya diverifikasi **saat anggota melakukan absensi**, bukan realtime.
- Sistem memverifikasi bahwa lokasi anggota berada di dalam radius area tempat ibadah saat absen.
- Radius geofencing diinput manual oleh admin (satuan meter), disimpan di kolom `radius_meter` di tabel `tempat_ibadah`.

---

## 4. Pendaftaran Tamu / Walk-in

Fitur untuk mengakomodasi peserta itikaf dari luar daerah atau luar negeri yang datang tanpa akun.

### Alur
1. Amir atau Pengurus mendaftarkan tamu langsung (on-the-spot) melalui sistem.
2. Data yang diinput:
   - Nama lengkap
   - Asal daerah (teks bebas, wajib diisi)
   - Foto wajah (diambil langsung via kamera untuk face enrollment)
3. Akun langsung aktif dengan status **"Tamu"**.
4. Tamu ditempatkan di wilayah default **"Tamu/Lainnya"** — buat via seeder jika belum ada.
5. Setelah face enrollment selesai, tamu bisa absensi via face recognition dan terverifikasi GPS seperti anggota biasa.
6. Data tamu tetap tersimpan setelah itikaf untuk rekap/laporan.
7. Pengurus Inti dapat mengkonversi akun tamu menjadi anggota resmi jika diperlukan.

### Penyesuaian Database
- Tambahkan kolom `status` di tabel anggota/pengguna: `aktif`, `tamu`, `nonaktif`.
- Tambahkan wilayah default "Tamu/Lainnya" via seeder.
- Buat halaman/form khusus pendaftaran tamu yang dapat diakses Amir dan Pengurus.
- Di dashboard dan laporan, beri label khusus untuk anggota berstatus "Tamu".

---

Tolong implementasikan semua fitur di atas sesuai struktur proyek yang sudah ada (Laravel/PHP + MySQL + Leaflet.js). Sesuaikan migration database, model, controller, dan view yang terkait.
