# Product Requirements Document (PRD)
# Sistem Informasi Manajemen Organisasi MARKAZ

---

**Versi Dokumen:** 1.1 (Revisi Peta & Face Recognition)
**Tanggal:** 2025  
**Disusun oleh:** Mahasiswa D3 Teknik Informatika  
**Status:** Draft Final  

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Gambaran Umum Produk](#2-gambaran-umum-produk)
3. [Tujuan & Sasaran](#3-tujuan--sasaran)
4. [Ruang Lingkup Sistem](#4-ruang-lingkup-sistem)
5. [Pengguna Sistem](#5-pengguna-sistem)
6. [Kebutuhan Fungsional](#6-kebutuhan-fungsional)
7. [Kebutuhan Non-Fungsional](#7-kebutuhan-non-fungsional)
8. [Arsitektur Sistem](#8-arsitektur-sistem)
9. [Desain Database](#9-desain-database)
10. [Alur Bisnis Utama](#10-alur-bisnis-utama)
11. [Integrasi Pihak Ketiga](#11-integrasi-pihak-ketiga)
12. [Keamanan Sistem](#12-keamanan-sistem)
13. [Batasan & Asumsi](#13-batasan--asumsi)
14. [Tahapan Pengembangan](#14-tahapan-pengembangan)
15. [Kriteria Penerimaan](#15-kriteria-penerimaan)

---

## 1. Pendahuluan

### 1.1 Latar Belakang

MARKAZ adalah organisasi keislaman yang berfokus pada kegiatan dakwah, khususnya melalui program i'tikaf yang dilaksanakan secara berkelompok maupun kegiatan individual anggota jamaah. Dalam operasionalnya, MARKAZ memiliki struktur organisasi berjenjang mulai dari Pengurus Inti, Pengurus Halaqah Wilayah, hingga Anggota Jamaah yang tersebar di berbagai mahallah (masjid).

Saat ini pengelolaan kegiatan, absensi, dan pelaporan masih dilakukan secara manual, sehingga menimbulkan berbagai kendala seperti ketidakakuratan data kehadiran, keterlambatan pelaporan, dan sulitnya pemantauan kegiatan anggota secara real-time. Oleh karena itu, dibutuhkan sebuah sistem informasi berbasis web dan mobile yang mampu mengotomatisasi dan mengdigitalisasi proses-proses tersebut.

### 1.2 Tujuan Dokumen

Dokumen PRD ini bertujuan untuk:
- Mendefinisikan kebutuhan fungsional dan non-fungsional sistem secara lengkap
- Menjadi acuan pengembangan bagi tim developer
- Menjadi dasar evaluasi dan pengujian sistem
- Mendokumentasikan kesepakatan antara pemangku kepentingan dan pengembang

### 1.3 Definisi & Akronim

| Istilah | Definisi |
|---|---|
| MARKAZ | Nama organisasi keislaman yang menjadi objek studi kasus |
| I'tikaf | Kegiatan berdiam diri di masjid untuk beribadah yang menjadi kegiatan inti MARKAZ |
| Halaqah Wilayah | Divisi organisasi berdasarkan wilayah geografis |
| Mahallah | Masjid yang menjadi basis kegiatan anggota dalam satu wilayah |
| Amir I'tikaf | Anggota jamaah yang ditunjuk sebagai penanggung jawab pelaksanaan i'tikaf |
| Face Recognition | Teknologi pengenalan wajah untuk validasi identitas pengguna |
| Geofencing | Teknologi pembatas area geografis virtual berbasis GPS |
| PRD | Product Requirements Document |
| FCM | Firebase Cloud Messaging |
| AWS | Amazon Web Services |

---

## 2. Gambaran Umum Produk

### 2.1 Deskripsi Produk

Sistem Informasi MARKAZ adalah aplikasi berbasis web dan mobile yang dirancang untuk mengelola seluruh aspek operasional organisasi MARKAZ, mulai dari manajemen keanggotaan, penjadwalan dan pelaksanaan i'tikaf, pencatatan kegiatan individual, pelaporan, hingga dashboard statistik. Sistem ini mengintegrasikan teknologi face recognition berbasis AWS Rekognition untuk validasi absensi dan GPS geofencing untuk memastikan kehadiran fisik pada lokasi i'tikaf yang telah ditentukan.

**Sistem Face Recognition** pada aplikasi ini menggunakan prinsip **Face Embedding & Verification**. Saat registrasi, sistem tidak hanya menyimpan gambar, melainkan mengekstrak fitur wajah menjadi vektor data matematis (*FaceId*) yang dienkripsi. Saat absensi, sistem melakukan pemindaian wajah secara *real-time* dengan teknologi **Liveness Detection** (Anti-Spoofing) untuk mencegah kecurangan menggunakan foto cetak atau layar HP, dan mencocokkannya dengan *FaceId* awal dengan tingkat akurasi (Similarity Score) minimal **90%**.

### 2.2 Platform

| Platform | Teknologi | Target Pengguna |
|---|---|---|
| Web (Desktop & Mobile Browser) | Laravel Blade | Pengurus Inti, Pengurus Wilayah |
| Mobile App (iOS & Android) | React Native | Anggota Jamaah, Amir I'tikaf |

### 2.3 Lingkungan Pengembangan

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel (PHP) |
| Web Frontend | Laravel Blade + TailwindCSS |
| Mobile App | React Native |
| Database | MySQL |
| Face Recognition API | AWS Rekognition |
| GPS & Peta | Google Maps API |
| Push Notification | Firebase Cloud Messaging (FCM) |
| File Storage | AWS S3 |
| Export PDF | DomPDF (barryvdh/laravel-dompdf) |
| Export Excel | Maatwebsite/Laravel-Excel |
| Authentication | Laravel Sanctum (API Token) |

---

## 3. Tujuan & Sasaran

### 3.1 Tujuan Utama

1. Mengdigitalisasi proses manajemen organisasi MARKAZ secara menyeluruh
2. Meningkatkan akurasi dan keamanan absensi menggunakan face recognition
3. Memastikan validitas lokasi pelaksanaan i'tikaf melalui GPS geofencing
4. Mempercepat alur pelaporan kegiatan dari Amir ke Pengurus Inti
5. Memberikan visibilitas data real-time kepada seluruh jenjang pengurus

### 3.2 Sasaran yang Dapat Diukur

| Sasaran | Indikator Keberhasilan |
|---|---|
| Akurasi absensi | Tingkat keberhasilan face recognition ≥ 95% |
| Validasi lokasi | GPS radius 100 meter, error tolerance ≤ 10 meter |
| Kecepatan laporan | Laporan sesi dapat dikirim dalam waktu < 5 menit |
| Skalabilitas | Mendukung 100–500 anggota aktif secara bersamaan |
| Ketersediaan sistem | Uptime ≥ 99% selama periode aktif kegiatan |

---

## 4. Ruang Lingkup Sistem

### 4.1 Yang Termasuk dalam Sistem

- Manajemen akun pengguna (registrasi, login, profil, enrollment wajah)
- Manajemen struktur organisasi (wilayah, mahallah, keanggotaan)
- Visualisasi peta interaktif (Geospatial Dashboard) untuk persebaran Mahallah berdasarkan Halaqah Wilayah dengan custom pin/marker.
- Penjadwalan dan pengelolaan i'tikaf
- Pemilihan peserta dan penugasan Amir I'tikaf
- Absensi i'tikaf dengan face recognition dan validasi GPS
- Absensi kegiatan individual dengan face recognition
- Pelaporan sesi i'tikaf oleh Amir
- Alur persetujuan laporan berjenjang
- Manajemen target/kuota kegiatan individual
- Dashboard statistik per level pengguna
- Export laporan dalam format PDF dan Excel
- Push notification dan in-app notification

### 4.2 Yang Tidak Termasuk dalam Sistem

- Sistem keuangan atau pengelolaan kas organisasi
- Fitur komunikasi/chat antar anggota
- Manajemen konten dakwah (artikel, video)
- Integrasi dengan sistem informasi masjid lain
- Fitur donasi atau pembayaran online

---

## 5. Pengguna Sistem

### 5.1 Hierarki Pengguna

```
┌─────────────────────────────────┐
│         PENGURUS INTI           │  ← Akses penuh semua modul
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│    PENGURUS HALAQAH WILAYAH     │  ← Akses manajemen wilayahnya
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│         ANGGOTA JAMAAH          │  ← Absensi, kegiatan, profil
│    (dapat menjadi AMIR I'TIKAF) │  ← + laporan sesi i'tikaf
└─────────────────────────────────┘
```

### 5.2 Deskripsi Role Pengguna

#### 5.2.1 Pengurus Inti
- **Cara Pembuatan Akun:** Laravel Database Seeder
- **Akses Platform:** Web (utama)
- **Tanggung Jawab:**
  - Mengelola seluruh data organisasi (wilayah, mahallah, anggota)
  - Membuat dan mengelola jadwal i'tikaf
  - Menunjuk Amir I'tikaf
  - Menerima dan menyetujui laporan sesi dari Pengurus Wilayah
  - Melihat dashboard statistik global
  - Menetapkan target/kuota kegiatan individual
  - Mengekspor laporan rekap dalam PDF/Excel

#### 5.2.2 Pengurus Halaqah Wilayah
- **Cara Pembuatan Akun:** Laravel Database Seeder
- **Akses Platform:** Web (utama)
- **Tanggung Jawab:**
  - Mengelola data anggota di wilayah dan mahallah-nya
  - Memilih peserta i'tikaf dari anggota wilayahnya
  - Menerima dan meneruskan laporan dari Amir ke Pengurus Inti
  - Melihat dashboard statistik wilayahnya
  - Mengekspor laporan rekap wilayah

#### 5.2.3 Anggota Jamaah
- **Cara Pembuatan Akun:** Registrasi mandiri (langsung aktif)
- **Akses Platform:** Mobile App (utama), Web (terbatas)
- **Tanggung Jawab:**
  - Mendaftarkan akun dan melakukan enrollment wajah
  - Melakukan absensi i'tikaf (face recognition + GPS)
  - Melakukan absensi kegiatan individual (face recognition)
  - Melihat riwayat kegiatan dan progres target pribadi

#### 5.2.4 Amir I'tikaf
- **Cara Penetapan:** Ditugaskan oleh Pengurus Inti dari daftar peserta i'tikaf
- **Akses Platform:** Mobile App (utama)
- **Tanggung Jawab:** Seluruh akses Anggota Jamaah, ditambah:
  - Membuat laporan per sesi kegiatan i'tikaf
  - Mengisi form terstruktur laporan + upload foto/dokumen
  - Mengirim laporan ke Pengurus Wilayah

---

## 6. Kebutuhan Fungsional

### 6.1 Modul Autentikasi & Manajemen Akun (M1)

#### F1.1 Registrasi Anggota Jamaah
- Sistem menyediakan form registrasi dengan field: nama lengkap, email, password, nomor telepon, jenis kelamin, tanggal lahir, wilayah, mahallah
- Setelah mengisi data, anggota wajib melakukan enrollment wajah (mengunggah atau mengambil foto wajah dari minimal 3 sudut: depan, kiri, kanan)
- Foto wajah dikirim ke AWS Rekognition untuk disimpan sebagai face embedding
- Akun langsung aktif tanpa perlu verifikasi tambahan
- Sistem menyimpan referensi `FaceId` dari AWS Rekognition ke tabel `pendaftaran_wajah`

#### F1.2 Login
- Pengguna dapat login menggunakan email dan password
- Sistem menggunakan Laravel Sanctum untuk pengelolaan token autentikasi API
- Terdapat fitur "Lupa Password" dengan reset via email

#### F1.3 Manajemen Profil
- Pengguna dapat mengubah data profil (nama, telepon, foto profil)
- Halaman profil menampilkan: data diri, foto wajah terdaftar (thumbnail), wilayah, mahallah, riwayat kegiatan, dan statistik kehadiran
- Pengguna dapat memperbarui foto wajah untuk enrollment ulang ke AWS Rekognition

#### F1.4 Manajemen Role
- Pengurus Inti dapat mengubah role pengguna
- Penetapan Amir I'tikaf dilakukan per jadwal, bukan perubahan role permanen

---

### 6.2 Modul Manajemen Organisasi (M2)

#### F2.1 Manajemen Wilayah
- Pengurus Inti dapat membuat, mengubah, menonaktifkan, dan menghapus data Halaqah Wilayah
- Data wilayah mencakup: nama wilayah, deskripsi, Pengurus Wilayah yang bertanggung jawab

#### F2.2 Manajemen Mahallah
- Pengurus Inti dan Pengurus Wilayah dapat mengelola data mahallah dalam wilayahnya
- Data mahallah mencakup: nama mahallah, alamat masjid, koordinat GPS masjid, wilayah induk
- Setiap mahallah terhubung ke satu wilayah

#### F2.3 Manajemen Anggota
- Pengurus Inti dapat melihat seluruh anggota lintas wilayah
- Pengurus Wilayah dapat melihat dan mengelola anggota di wilayahnya
- Fitur pencarian dan filter anggota berdasarkan wilayah, mahallah, dan status
- Pengurus dapat menonaktifkan akun anggota yang tidak aktif

---

### 6.3 Modul I'tikaf (M3)

#### F3.1 Penjadwalan I'tikaf (Pengurus Inti)
- Pengurus Inti dapat membuat jadwal i'tikaf dengan mengisi:
  - Nama/tema i'tikaf
  - Tanggal mulai dan tanggal selesai
  - Nama lokasi pelaksanaan
  - Koordinat GPS lokasi (latitude, longitude) — dapat dipilih dari peta interaktif
  - Radius geofencing: **tetap 100 meter** (diatur oleh sistem)
  - Keterangan tambahan
- Setelah jadwal dibuat, notifikasi otomatis dikirim ke seluruh Pengurus Wilayah
- Pengurus Inti dapat mengubah atau membatalkan jadwal sebelum i'tikaf dimulai

#### F3.2 Pemilihan Peserta I'tikaf (Pengurus Wilayah)
- Pengurus Wilayah menerima notifikasi jadwal i'tikaf baru
- Pengurus Wilayah memilih anggota dari wilayahnya untuk menjadi peserta i'tikaf
- Daftar peserta yang dipilih dikirimkan ke Pengurus Inti
- Sistem mencatat peserta per jadwal i'tikaf di tabel `peserta_itikaf`

#### F3.3 Penugasan Amir I'tikaf (Pengurus Inti)
- Pengurus Inti menunjuk satu anggota dari daftar peserta sebagai Amir I'tikaf
- Setelah penugasan, notifikasi dikirim ke:
  - Amir yang ditunjuk (push notification)
  - Seluruh peserta i'tikaf (push notification)
- Satu i'tikaf hanya memiliki satu Amir

#### F3.4 Absensi I'tikaf (Anggota / Amir)
- Absensi dilakukan melalui aplikasi mobile
- Proses absensi:
  1. Anggota membuka fitur absensi i'tikaf
  2. Sistem mengaktifkan kamera untuk proses face recognition
  3. Foto wajah dikirim ke AWS Rekognition untuk dicocokkan dengan data enrollment
  4. Jika wajah terverifikasi, sistem memeriksa lokasi GPS perangkat anggota
  5. Sistem menghitung jarak antara lokasi anggota dengan koordinat i'tikaf yang telah ditetapkan
  6. Jika jarak ≤ 100 meter, absensi dinyatakan **berhasil**
  7. Jika jarak > 100 meter, absensi dinyatakan **gagal** dengan pesan kesalahan lokasi
  8. Jika wajah tidak dikenali, absensi dinyatakan **gagal** dengan pesan kesalahan identitas
- Data absensi disimpan di tabel `absensi_itikaf` beserta timestamp, koordinat GPS aktual, dan status

#### F3.5 Laporan Sesi I'tikaf (Amir)
- Amir dapat membuat laporan untuk setiap sesi kegiatan (bisa beberapa laporan dalam satu hari)
- Form laporan sesi mencakup:
  - Nama sesi kegiatan
  - Waktu mulai dan selesai sesi
  - Daftar peserta yang hadir pada sesi tersebut (checklist)
  - Uraian kegiatan (teks bebas)
  - Upload foto/dokumen pendukung (maks. 5 file, format: jpg, png, pdf)
- Laporan yang telah dibuat dapat diedit selama belum dikirim
- Amir mengirim laporan ke Pengurus Wilayah dengan tombol "Kirim Laporan"
- Notifikasi dikirim ke Pengurus Wilayah saat laporan baru masuk

#### F3.6 Alur Persetujuan Laporan
- **Pengurus Wilayah** menerima laporan dari Amir, dapat:
  - Menyetujui dan meneruskan ke Pengurus Inti
  - Mengembalikan laporan ke Amir dengan catatan revisi
- **Pengurus Inti** menerima laporan dari Pengurus Wilayah, dapat:
  - Menyetujui laporan (status final: Disetujui)
  - Mengembalikan laporan ke Pengurus Wilayah dengan catatan
- Notifikasi dikirim di setiap perubahan status laporan

| Status Laporan | Keterangan |
|---|---|
| Draft | Laporan dibuat, belum dikirim |
| Menunggu Wilayah | Dikirim Amir, menunggu review Pengurus Wilayah |
| Dikembalikan (Wilayah) | Dikembalikan ke Amir untuk revisi |
| Menunggu Inti | Diteruskan Pengurus Wilayah ke Pengurus Inti |
| Dikembalikan (Inti) | Dikembalikan ke Pengurus Wilayah |
| Disetujui | Disetujui Pengurus Inti, laporan final |

---

### 6.4 Modul Kegiatan Individual (M4)

#### F4.1 Master Jenis Kegiatan
- Pengurus Inti dapat menambah, mengubah, dan menonaktifkan jenis kegiatan individual
- Contoh jenis kegiatan bawaan sistem:
  - Kunjungan Mahasiswa
  - Kunjungan Guru
  - Duduk Ta'lim Majelis
  - Halaqah Pribadi
  - Kegiatan Lainnya

#### F4.2 Manajemen Target/Kuota Kegiatan
- Pengurus Inti dapat menetapkan target jumlah kegiatan per jenis per periode (bulanan/tahunan)
- Target berlaku untuk seluruh anggota aktif
- Pengurus Inti dapat mengubah target selama periode berjalan
- Anggota dapat melihat target yang berlaku dan progres pencapaiannya

#### F4.3 Absensi Kegiatan Individual
- Absensi dilakukan melalui aplikasi mobile
- Proses absensi:
  1. Anggota membuka fitur absensi kegiatan individual
  2. Anggota memilih jenis kegiatan yang dilaksanakan
  3. Sistem mengaktifkan kamera untuk proses face recognition
  4. Foto wajah dikirim ke AWS Rekognition untuk verifikasi identitas
  5. Jika wajah terverifikasi, kegiatan tercatat di sistem (tanpa validasi GPS)
  6. Jika wajah tidak dikenali, absensi gagal
- Data kegiatan disimpan di tabel `absensi_kegiatan` beserta timestamp dan jenis kegiatan
- Progres target anggota diperbarui otomatis

---

### 6.5 Modul Notifikasi (M5)

#### F5.1 Jenis Notifikasi

| Trigger Event | Penerima | Kanal |
|---|---|---|
| Jadwal i'tikaf baru dibuat | Pengurus Wilayah | Push + In-App |
| Pengurus Wilayah telah memilih peserta | Pengurus Inti | Push + In-App |
| Ditugaskan sebagai peserta i'tikaf | Anggota terpilih | Push + In-App |
| Ditunjuk sebagai Amir I'tikaf | Amir terpilih | Push + In-App |
| Jadwal i'tikaf H-1 | Seluruh peserta | Push + In-App |
| Laporan sesi baru diterima | Pengurus Wilayah | Push + In-App |
| Laporan diteruskan ke Pengurus Inti | Pengurus Inti | Push + In-App |
| Laporan dikembalikan untuk revisi | Amir / Pengurus Wilayah | Push + In-App |
| Laporan disetujui | Amir | Push + In-App |
| Target kegiatan individual telah tercapai | Anggota bersangkutan | Push + In-App |

#### F5.2 Manajemen Notifikasi
- Pengguna dapat melihat riwayat notifikasi di halaman notifikasi in-app
- Notifikasi yang belum dibaca ditandai dengan indikator visual
- Pengguna dapat menandai semua notifikasi sebagai telah dibaca

---

### 6.6 Modul Dashboard & Laporan (M6)

#### F6.1 Dashboard Pengurus Inti
- Statistik global:
  - Total anggota aktif per wilayah
  - Jumlah i'tikaf yang telah dilaksanakan
  - Grafik kehadiran i'tikaf per periode
  - Rekap kegiatan individual per wilayah
  - Progres pencapaian target kegiatan
- Tabel daftar laporan i'tikaf terbaru beserta statusnya

#### F6.2 Dashboard Pengurus Wilayah
- Statistik wilayahnya:
  - Total anggota aktif di wilayah
  - Jumlah peserta i'tikaf dari wilayahnya
  - Grafik kehadiran anggota di setiap i'tikaf
  - Rekap kegiatan individual anggota wilayah
  - Laporan sesi yang menunggu review

#### F6.3 Dashboard Anggota
- Profil singkat dan status keanggotaan
- Riwayat kehadiran i'tikaf
- Riwayat kegiatan individual
- Progres pencapaian target kegiatan (visual progress bar per jenis kegiatan)

#### F6.4 Export Laporan
- **Format yang tersedia:** PDF dan Excel (.xlsx)
- **Jenis laporan yang dapat diekspor:**
  - Rekap kehadiran i'tikaf (per jadwal / per periode)
  - Detail laporan sesi i'tikaf beserta lampiran
  - Rekap kegiatan individual per anggota
  - Rekap pencapaian target kegiatan per periode
  - Daftar anggota per wilayah/mahallah
- Export dapat difilter berdasarkan periode tanggal, wilayah, dan mahallah

---

## 7. Kebutuhan Non-Fungsional

### 7.1 Performa
- Halaman web harus termuat dalam waktu < 3 detik pada koneksi normal
- Proses face recognition (termasuk komunikasi dengan AWS Rekognition) harus selesai dalam < 5 detik
- Validasi GPS harus selesai dalam < 3 detik
- Sistem harus mampu menangani hingga 500 pengguna aktif secara bersamaan

### 7.2 Keamanan
- Seluruh komunikasi antara client dan server menggunakan HTTPS/TLS
- Password disimpan menggunakan bcrypt hashing (Laravel default)
- API dilindungi dengan Laravel Sanctum token-based authentication
- Data wajah tidak disimpan secara lokal; hanya `FaceId` dari AWS yang disimpan
- Koordinat GPS tidak disimpan secara permanen setelah validasi absensi
- Akses endpoint API dibatasi berdasarkan role menggunakan Laravel middleware

### 7.3 Ketersediaan
- Sistem harus tersedia 99% uptime selama periode aktif kegiatan
- Sistem harus memiliki mekanisme penanganan error yang informatif

### 7.4 Skalabilitas
- Arsitektur harus mendukung penambahan wilayah dan mahallah baru tanpa modifikasi kode
- Database harus diindeks dengan baik untuk mendukung query laporan yang kompleks

### 7.5 Usabilitas
- Antarmuka menggunakan Bahasa Indonesia
- Aplikasi mobile harus mendukung iOS 13+ dan Android 8+
- Ukuran font dan elemen UI harus mempertimbangkan kenyamanan pengguna dari berbagai usia
- Proses absensi (face recognition) harus dapat diselesaikan dalam maksimal 3 langkah tap

### 7.6 Pemeliharaan
- Kode mengikuti standar PSR-12 untuk PHP (Laravel)
- Seluruh API didokumentasikan menggunakan format yang konsisten
- Menggunakan Laravel migrations untuk pengelolaan skema database

---

## 8. Arsitektur Sistem

### 8.1 Gambaran Arsitektur

```
┌─────────────────────────────────────────────────────────┐
│                     CLIENT LAYER                        │
│  ┌──────────────────────┐  ┌──────────────────────────┐ │
│  │   Web Browser        │  │   React Native App       │ │
│  │   (Laravel Blade)    │  │   (iOS & Android)        │ │
│  └──────────┬───────────┘  └────────────┬─────────────┘ │
└─────────────┼───────────────────────────┼───────────────┘
              │ HTTPS                     │ HTTPS (REST API)
┌─────────────▼───────────────────────────▼───────────────┐
│                   APPLICATION LAYER                     │
│              Laravel Backend (REST API)                 │
│  ┌────────────┐ ┌─────────────┐ ┌───────────────────┐  │
│  │ Auth &     │ │ Business    │ │ Notification      │  │
│  │ Middleware │ │ Logic       │ │ Service (FCM)     │  │
│  └────────────┘ └─────────────┘ └───────────────────┘  │
└──────────┬──────────────┬───────────────┬───────────────┘
           │              │               │
┌──────────▼───┐  ┌───────▼──────┐ ┌─────▼──────────────┐
│   MySQL DB   │  │   AWS S3     │ │  AWS Rekognition    │
│  (Data utama)│  │(File Storage)│ │  (Face Recognition) │
└──────────────┘  └──────────────┘ └────────────────────┘
```

### 8.2 Pola Komunikasi

- **Web Frontend ↔ Backend:** Server-side rendering dengan Laravel Blade; form submission dan AJAX untuk interaksi dinamis
- **Mobile App ↔ Backend:** RESTful API dengan JSON payload; autentikasi menggunakan Bearer Token (Laravel Sanctum)
- **Backend ↔ AWS Rekognition:** AWS SDK for PHP; proses index face dan search face
- **Backend ↔ AWS S3:** AWS SDK for PHP; upload dan retrieve file lampiran laporan
- **Backend ↔ FCM:** HTTP v1 API Firebase; pengiriman push notification

---

## 9. Desain Database

### 9.1 Daftar Tabel

| Tabel | Deskripsi |
|---|---|
| `pengguna` | Data seluruh pengguna sistem beserta role |
| `wilayah` | Data Halaqah Wilayah |
| `mahallah` | Data masjid per wilayah |
| `jadwal_itikaf` | Jadwal i'tikaf (lokasi, koordinat GPS, tanggal) |
| `peserta_itikaf` | Relasi anggota yang menjadi peserta per jadwal |
| `absensi_itikaf` | Rekam absensi i'tikaf (hasil face + hasil GPS) |
| `laporan_itikaf` | Laporan sesi kegiatan yang dibuat oleh Amir |
| `berkas_laporan` | File lampiran untuk setiap laporan sesi |
| `jenis_kegiatan` | Master data jenis kegiatan individual |
| `target_kegiatan` | Target/kuota kegiatan per jenis per periode |
| `absensi_kegiatan` | Rekam absensi kegiatan individual anggota |
| `notifikasi` | Log notifikasi in-app per pengguna |
| `pendaftaran_wajah` | Referensi AWS Rekognition FaceId per pengguna |

### 9.2 Skema Tabel Utama

#### Tabel `pengguna`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| nama_lengkap | VARCHAR(100) | Nama lengkap pengguna |
| email | VARCHAR(100) | Email unik, digunakan untuk login |
| password | VARCHAR(255) | Bcrypt hashed |
| no_telepon | VARCHAR(20) | Nomor telepon |
| jenis_kelamin | ENUM | 'L' atau 'P' |
| tanggal_lahir | DATE | Tanggal lahir |
| role | ENUM | 'pengurus_inti', 'pengurus_wilayah', 'anggota' |
| wilayah_id | BIGINT (FK) | Referensi ke tabel wilayah |
| mahallah_id | BIGINT (FK) | Referensi ke tabel mahallah |
| foto_profil | VARCHAR(255) | Path foto di AWS S3 |
| fcm_token | VARCHAR(255) | Token FCM untuk push notification |
| status | ENUM | 'aktif', 'nonaktif' |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `wilayah`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| nama_wilayah | VARCHAR(100) | Nama Halaqah Wilayah |
| deskripsi | TEXT | Keterangan wilayah |
| pengurus_id | BIGINT (FK) | Referensi ke tabel pengguna (Pengurus Wilayah) |
| status | ENUM | 'aktif', 'nonaktif' |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `mahallah`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| nama_mahallah | VARCHAR(100) | Nama masjid |
| alamat | TEXT | Alamat lengkap masjid |
| latitude | DECIMAL(10,8) | Koordinat GPS latitude |
| longitude | DECIMAL(11,8) | Koordinat GPS longitude |
| wilayah_id | BIGINT (FK) | Referensi ke tabel wilayah |
| status | ENUM | 'aktif', 'nonaktif' |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `jadwal_itikaf`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| nama_itikaf | VARCHAR(150) | Nama/tema i'tikaf |
| tanggal_mulai | DATE | Tanggal mulai i'tikaf |
| tanggal_selesai | DATE | Tanggal selesai i'tikaf |
| nama_lokasi | VARCHAR(150) | Nama lokasi pelaksanaan |
| latitude | DECIMAL(10,8) | Koordinat GPS lokasi |
| longitude | DECIMAL(11,8) | Koordinat GPS lokasi |
| radius_meter | INT | Default: 100 (tetap) |
| keterangan | TEXT | Keterangan tambahan |
| dibuat_oleh | BIGINT (FK) | Referensi ke tabel pengguna (Pengurus Inti) |
| status | ENUM | 'dijadwalkan', 'berlangsung', 'selesai', 'dibatalkan' |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `peserta_itikaf`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| jadwal_itikaf_id | BIGINT (FK) | Referensi ke jadwal_itikaf |
| pengguna_id | BIGINT (FK) | Referensi ke tabel pengguna |
| adalah_amir | BOOLEAN | True jika ditunjuk sebagai Amir |
| dipilih_oleh | BIGINT (FK) | Pengurus Wilayah yang memilih |
| created_at | TIMESTAMP | |

#### Tabel `absensi_itikaf`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| jadwal_itikaf_id | BIGINT (FK) | Referensi ke jadwal_itikaf |
| pengguna_id | BIGINT (FK) | Referensi ke tabel pengguna |
| waktu_absen | TIMESTAMP | Waktu absensi dilakukan |
| latitude_aktual | DECIMAL(10,8) | Koordinat GPS saat absen |
| longitude_aktual | DECIMAL(11,8) | Koordinat GPS saat absen |
| jarak_meter | INT | Jarak dari titik i'tikaf |
| status_gps | ENUM | 'valid', 'diluar_radius' |
| status_wajah | ENUM | 'dikenali', 'tidak_dikenali' |
| status_absen | ENUM | 'berhasil', 'gagal' |
| keterangan_gagal | VARCHAR(255) | Alasan jika gagal |
| created_at | TIMESTAMP | |

#### Tabel `laporan_itikaf`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| jadwal_itikaf_id | BIGINT (FK) | Referensi ke jadwal_itikaf |
| amir_id | BIGINT (FK) | Referensi ke tabel pengguna (Amir) |
| nama_sesi | VARCHAR(150) | Nama sesi kegiatan |
| waktu_mulai | DATETIME | Waktu mulai sesi |
| waktu_selesai | DATETIME | Waktu selesai sesi |
| uraian_kegiatan | TEXT | Deskripsi kegiatan sesi |
| peserta_hadir | JSON | Array ID peserta yang hadir |
| status | ENUM | 'draft', 'menunggu_wilayah', 'dikembalikan_wilayah', 'menunggu_inti', 'dikembalikan_inti', 'disetujui' |
| catatan_wilayah | TEXT | Catatan dari Pengurus Wilayah |
| catatan_inti | TEXT | Catatan dari Pengurus Inti |
| dikirim_pada | TIMESTAMP | Waktu pengiriman laporan |
| disetujui_pada | TIMESTAMP | Waktu persetujuan akhir |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `berkas_laporan`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| laporan_itikaf_id | BIGINT (FK) | Referensi ke laporan_itikaf |
| nama_berkas | VARCHAR(255) | Nama file asli |
| path_s3 | VARCHAR(500) | Path file di AWS S3 |
| tipe_berkas | VARCHAR(50) | MIME type (image/jpeg, application/pdf, dll) |
| ukuran_berkas | INT | Ukuran file dalam bytes |
| created_at | TIMESTAMP | |

#### Tabel `jenis_kegiatan`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| nama_kegiatan | VARCHAR(100) | Nama jenis kegiatan |
| deskripsi | TEXT | Keterangan jenis kegiatan |
| status | ENUM | 'aktif', 'nonaktif' |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `target_kegiatan`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| jenis_kegiatan_id | BIGINT (FK) | Referensi ke jenis_kegiatan |
| jumlah_target | INT | Jumlah target yang harus dicapai |
| periode | ENUM | 'bulanan', 'tahunan' |
| tahun | YEAR | Tahun berlakunya target |
| bulan | TINYINT | Bulan berlakunya (jika periode bulanan) |
| ditetapkan_oleh | BIGINT (FK) | Referensi ke tabel pengguna (Pengurus Inti) |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `absensi_kegiatan`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| pengguna_id | BIGINT (FK) | Referensi ke tabel pengguna |
| jenis_kegiatan_id | BIGINT (FK) | Referensi ke jenis_kegiatan |
| waktu_kegiatan | TIMESTAMP | Waktu absensi dilakukan |
| status_wajah | ENUM | 'dikenali', 'tidak_dikenali' |
| status_absen | ENUM | 'berhasil', 'gagal' |
| created_at | TIMESTAMP | |

#### Tabel `notifikasi`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| pengguna_id | BIGINT (FK) | Penerima notifikasi |
| judul | VARCHAR(150) | Judul notifikasi |
| pesan | TEXT | Isi pesan notifikasi |
| tipe | VARCHAR(50) | Kategori notifikasi |
| referensi_id | BIGINT | ID entitas terkait (opsional) |
| referensi_tipe | VARCHAR(50) | Tipe entitas terkait (opsional) |
| dibaca | BOOLEAN | Status baca notifikasi |
| dibaca_pada | TIMESTAMP | Waktu notifikasi dibaca |
| created_at | TIMESTAMP | |

#### Tabel `pendaftaran_wajah`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT (PK) | Auto increment |
| pengguna_id | BIGINT (FK) | Referensi ke tabel pengguna |
| aws_face_id | VARCHAR(100) | FaceId dari AWS Rekognition |
| aws_collection_id | VARCHAR(100) | Collection ID di AWS Rekognition |
| status | ENUM | 'aktif', 'nonaktif' |
| terdaftar_pada | TIMESTAMP | Waktu enrollment dilakukan |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

## 10. Alur Bisnis Utama

### 10.1 Alur Registrasi & Enrollment Wajah

```
[Anggota]
  │
  ▼
Buka halaman registrasi
  │
  ▼
Isi form data diri (nama, email, password, telepon,
wilayah, mahallah)
  │
  ▼
Upload/ambil foto wajah (3 sudut: depan, kiri, kanan)
  │
  ▼
Sistem kirim foto ke AWS Rekognition
  │
  ├── [Wajah terdeteksi] → Simpan FaceId ke tabel pendaftaran_wajah
  │                      → Akun langsung aktif
  │                      → Redirect ke halaman dashboard
  │
  └── [Wajah tidak terdeteksi] → Tampilkan pesan error
                               → Minta upload ulang foto
```

### 10.2 Alur Lengkap I'tikaf

```
[Pengurus Inti]
  │
  ▼
Buat jadwal i'tikaf (nama, tanggal, lokasi, koordinat GPS)
  │
  ▼
Notifikasi terkirim ke semua Pengurus Wilayah
  │
  ▼
[Pengurus Wilayah] Menerima notifikasi → Pilih peserta dari anggotanya
  │
  ▼
[Pengurus Inti] Menerima daftar peserta → Tunjuk Amir I'tikaf
  │
  ▼
Notifikasi terkirim ke seluruh peserta & Amir
  │
  ▼
[Hari Pelaksanaan]
  │
  ▼
[Anggota] Buka app → Pilih Absensi I'tikaf
  │
  ├── Kamera aktif → Ambil foto wajah
  │     │
  │     ├── [Wajah dikenali] → Validasi GPS
  │     │     │
  │     │     ├── [Dalam radius 100m] → Absensi BERHASIL ✓
  │     │     │
  │     │     └── [Di luar radius]   → Absensi GAGAL (lokasi tidak valid) ✗
  │     │
  │     └── [Wajah tidak dikenali]   → Absensi GAGAL (identitas tidak valid) ✗
  │
  ▼
[Amir] Buat laporan per sesi kegiatan
  │     (form terstruktur + upload foto/dokumen)
  │
  ▼
Kirim laporan ke Pengurus Wilayah
  │
  ▼
[Pengurus Wilayah] Review laporan
  │
  ├── [Disetujui] → Teruskan ke Pengurus Inti
  │
  └── [Dikembalikan] → Notifikasi ke Amir + catatan revisi
                     → Amir perbaiki & kirim ulang
  │
  ▼
[Pengurus Inti] Review laporan final
  │
  ├── [Disetujui] → Status laporan: DISETUJUI ✓
  │                → Notifikasi ke Amir
  │
  └── [Dikembalikan] → Notifikasi ke Pengurus Wilayah + catatan
```

### 10.3 Alur Absensi Kegiatan Individual

```
[Anggota]
  │
  ▼
Buka app → Pilih Absensi Kegiatan Individual
  │
  ▼
Pilih jenis kegiatan dari daftar yang tersedia
  │
  ▼
Kamera aktif → Ambil foto wajah
  │
  ├── [Wajah dikenali] → Kegiatan tercatat
  │                    → Progres target diperbarui
  │                    → Tampilkan konfirmasi ✓
  │
  └── [Wajah tidak dikenali] → Tampilkan pesan error ✗
                             → Minta coba lagi
```

---

## 11. Integrasi Pihak Ketiga

### 11.1 AWS Rekognition

| Aspek | Detail |
|---|---|
| Kegunaan | Face enrollment dan face verification untuk absensi |
| Endpoint | AWS Rekognition API (region: ap-southeast-1 / sesuai kebutuhan) |
| Operasi yang digunakan | `IndexFaces` (enrollment), `SearchFacesByImage` (verifikasi) |
| Library | AWS SDK for PHP (`aws/aws-sdk-php`) |
| Penyimpanan | Hanya `FaceId` yang disimpan di database lokal; foto tidak disimpan |
| Threshold | Confidence score ≥ 90% untuk dinyatakan cocok |

### 11.2 Google Maps API

| Aspek | Detail |
|---|---|
| Kegunaan | Peta interaktif untuk memilih lokasi i'tikaf, perhitungan jarak geofencing |
| Library Web | Google Maps JavaScript API |
| Library Mobile | React Native Maps |
| Kalkulasi Jarak | Haversine formula di sisi backend untuk validasi radius |

### 11.3 Firebase Cloud Messaging (FCM)

| Aspek | Detail |
|---|---|
| Kegunaan | Push notification ke aplikasi mobile |
| Library | `laravel-notification-channels/fcm` atau HTTP v1 API langsung |
| Token | FCM token disimpan di kolom `fcm_token` tabel `pengguna` |
| Update Token | Token diperbarui setiap kali aplikasi dibuka |

### 11.4 AWS S3

| Aspek | Detail |
|---|---|
| Kegunaan | Penyimpanan file lampiran laporan sesi, foto profil |
| Library | AWS SDK for PHP, Laravel Filesystem S3 driver |
| Struktur Folder | `laporan/{jadwal_id}/{laporan_id}/`, `profil/{pengguna_id}/` |
| Akses | Private bucket; URL ditandatangani sementara (signed URL) untuk akses |

---

## 12. Keamanan Sistem

### 12.1 Autentikasi & Otorisasi
- Semua endpoint API dilindungi middleware `auth:sanctum`
- Otorisasi berbasis role menggunakan Laravel Gates dan Policies
- Token API kadaluarsa setelah 30 hari tidak aktif

### 12.2 Perlindungan Data
- Komunikasi client-server wajib menggunakan HTTPS
- Password menggunakan bcrypt hashing
- Data sensitif (koordinat GPS, foto) tidak di-log di server
- AWS S3 bucket dikonfigurasi private; akses hanya melalui signed URL

### 12.3 Validasi Input
- Semua input divalidasi menggunakan Laravel Form Request Validation
- Upload file dibatasi tipe (jpg, png, pdf) dan ukuran (maksimal 5MB per file)
- Rate limiting pada endpoint absensi untuk mencegah spam

### 12.4 Audit Trail
- Seluruh aksi penting (login, absensi, pengiriman laporan, perubahan status) dicatat dengan timestamp dan pengguna yang melakukan aksi

---

## 13. Batasan & Asumsi

### 13.1 Batasan Sistem
- Face recognition hanya berfungsi optimal pada kondisi pencahayaan yang cukup
- Validasi GPS bergantung pada ketersediaan sinyal GPS di perangkat pengguna
- Radius geofencing 100 meter bersifat tetap dan tidak dapat diubah per jadwal
- Sistem tidak mendukung offline mode; membutuhkan koneksi internet untuk absensi
- Push notification hanya tersedia untuk pengguna mobile app (bukan web)
- Maksimal 5 file lampiran per laporan sesi dengan ukuran masing-masing ≤ 5MB

### 13.2 Asumsi
- Seluruh anggota jamaah memiliki smartphone dengan kamera depan
- Lokasi pelaksanaan i'tikaf selalu memiliki akses internet yang memadai
- AWS Rekognition dapat membedakan wajah yang mirip secara akurat
- Pengurus Inti dan Pengurus Wilayah memiliki akses ke komputer/laptop untuk penggunaan web
- Data seed awal (akun Pengurus Inti dan Pengurus Wilayah) disiapkan sebelum sistem diluncurkan

---

## 14. Tahapan Pengembangan

### Fase 1 — Fondasi Sistem (Minggu 1–3)
- Setup project Laravel (backend API + web)
- Setup project React Native (mobile)
- Konfigurasi database MySQL, migrasi tabel
- Implementasi autentikasi (registrasi, login, Laravel Sanctum)
- Integrasi AWS Rekognition untuk enrollment wajah
- Manajemen role dan middleware

### Fase 2 — Manajemen Organisasi (Minggu 4–5)
- CRUD Wilayah dan Mahallah
- Manajemen keanggotaan (tampil, filter, nonaktifkan)
- Halaman profil anggota

### Fase 3 — Modul I'tikaf (Minggu 6–9)
- Penjadwalan i'tikaf (Pengurus Inti)
- Pemilihan peserta dan penugasan Amir
- Absensi i'tikaf (face recognition + GPS geofencing)
- Laporan sesi (form + upload file ke S3)
- Alur persetujuan laporan berjenjang

### Fase 4 — Kegiatan Individual & Notifikasi (Minggu 10–11)
- Master jenis kegiatan dan target/kuota
- Absensi kegiatan individual (face recognition)
- Integrasi Firebase Cloud Messaging (push notification)
- In-app notification

### Fase 5 — Dashboard & Laporan (Minggu 12–13)
- Dashboard statistik per role
- Fitur export PDF (DomPDF)
- Fitur export Excel (Maatwebsite)

### Fase 6 — Pengujian & Finalisasi (Minggu 14–16)
- Unit testing dan integration testing
- User acceptance testing (UAT) bersama pihak MARKAZ
- Perbaikan bug dan penyempurnaan UI
- Deployment ke server produksi
- Dokumentasi sistem (user manual & API documentation)

---

## 15. Kriteria Penerimaan

Sistem dinyatakan diterima dan siap digunakan apabila memenuhi seluruh kriteria berikut:

| No | Kriteria | Indikator |
|---|---|---|
| 1 | Registrasi & enrollment wajah | Anggota dapat mendaftar dan foto wajah berhasil tersimpan di AWS Rekognition |
| 2 | Login & autentikasi | Semua role dapat login dan mengakses halaman sesuai hak aksesnya |
| 3 | Manajemen organisasi | Pengurus dapat CRUD wilayah, mahallah, dan anggota |
| 4 | Penjadwalan i'tikaf | Pengurus Inti dapat membuat jadwal dan notifikasi terkirim ke Pengurus Wilayah |
| 5 | Pemilihan peserta & Amir | Pengurus Wilayah dapat memilih peserta; Pengurus Inti dapat menunjuk Amir |
| 6 | Absensi i'tikaf | Face recognition + GPS berjalan; absensi berhasil jika wajah dikenali dan dalam radius 100m |
| 7 | Laporan sesi | Amir dapat membuat laporan multi-sesi dengan upload file |
| 8 | Alur persetujuan laporan | Laporan mengalir dari Amir → Pengurus Wilayah → Pengurus Inti dengan notifikasi di setiap tahap |
| 9 | Kegiatan individual | Absensi kegiatan berhasil dengan face recognition; progres target terupdate |
| 10 | Target kegiatan | Pengurus Inti dapat menetapkan target; anggota dapat melihat progres |
| 11 | Push notification | Notifikasi terkirim ke perangkat mobile untuk semua trigger event yang ditentukan |
| 12 | Dashboard | Setiap role melihat data statistik yang relevan dengan aksesnya |
| 13 | Export laporan | Laporan dapat diekspor dalam format PDF dan Excel dengan data yang akurat |
| 14 | Keamanan | Tidak ada endpoint yang dapat diakses tanpa autentikasi; akses lintas role terblokir |

---

*Dokumen ini merupakan hasil kesepakatan antara pengembang dan pemangku kepentingan. Segala perubahan kebutuhan setelah dokumen ini disahkan harus melalui proses revisi dokumen dengan persetujuan bersama.*

---

**Akhir Dokumen PRD v1.1**
