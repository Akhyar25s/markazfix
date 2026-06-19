# Dokumentasi Desain Database MARKAZ (CDM, LDM, PDM)

Dokumen ini berisi pemetaan terstruktur database proyek **MARKAZ** yang terbagi menjadi tiga tahapan: **Conceptual Data Model (CDM)**, **Logical Data Model (LDM)**, dan **Physical Data Model (PDM)**.

---

## ─── 1. CONCEPTUAL DATA MODEL (CDM) ───
CDM menggambarkan hubungan konseptual bisnis secara natural menggunakan entitas, atribut dasar, dan hubungan relasi (*cardinality*).

### A. Daftar Entitas & Atribut
* **User**: id, name, email, password, no_telepon, jenis_kelamin, tanggal_lahir, role, foto_profil, fcm_token, status
* **Wilayah**: id, nama_wilayah, deskripsi, status
* **Mahallah**: id, nama_mahallah, alamat, latitude, longitude, status
* **PendaftaranWajah**: id, aws_face_id, aws_collection_id, status, terdaftar_pada
* **JadwalItikaf**: id, nama_itikaf, tanggal_mulai, tanggal_selesai, nama_lokasi, latitude, longitude, radius_meter, keterangan, status
* **PesertaItikaf**: id, adalah_amir
* **LaporanItikaf**: id, nama_sesi, waktu_mulai, waktu_selesai, uraian_kegiatan, peserta_hadir, status, catatan_wilayah, catatan_inti, dikirim_pada, disetujui_pada, dokumen_pendukung
* **BerkasLaporan**: id, nama_berkas, path_s3, tipe_berkas, ukuran_berkas
* **AbsensiItikaf**: id, waktu_absen, latitude_aktual, longitude_aktual, jarak_meter, status_gps, status_wajah, status_absen, keterangan_gagal
* **JenisKegiatan**: id, nama_kegiatan, deskripsi, status
* **AbsensiKegiatan**: id, waktu_kegiatan, status_wajah, status_absen
* **TargetKegiatan**: id, jumlah_target, periode, tahun, bulan
* **Notifikasi**: id, judul, pesan, tipe, referensi_id, referensi_tipe, dibaca, dibaca_pada

### B. Hubungan Relasi Konseptual
```text
[Wilayah]           -- (1..n) Memiliki  -- [Mahallah]
[Wilayah]           -- (0..1) Dipimpin  -- [User] (pengurus_id)
[Mahallah]          -- (1..1) Memiliki  -- (0..n) [User] (mahallah_id)
[User]              -- (1..1) Memiliki  -- (0..1) [PendaftaranWajah] (pengguna_id)
[User]              -- (1..1) Membuat   -- (0..n) [JadwalItikaf] (dibuat_oleh)
[JadwalItikaf]      -- (1..1) Memiliki  -- (1..n) [PesertaItikaf] (jadwal_itikaf_id)
[User]              -- (1..1) Mendaftar -- (0..n) [PesertaItikaf] (pengguna_id)
[User]              -- (1..1) Memilih   -- (0..n) [PesertaItikaf] (dipilih_oleh)
[JadwalItikaf]      -- (1..1) Memiliki  -- (0..n) [LaporanItikaf] (jadwal_itikaf_id)
[User]              -- (1..1) Mengirim  -- (0..n) [LaporanItikaf] (amir_id)
[LaporanItikaf]     -- (1..1) Memiliki  -- (0..n) [BerkasLaporan] (laporan_itikaf_id)
[JadwalItikaf]      -- (1..1) Memiliki  -- (0..n) [AbsensiItikaf] (jadwal_itikaf_id)
[User]              -- (1..1) Melakukan -- (0..n) [AbsensiItikaf] (pengguna_id)
[User]              -- (1..1) Melakukan -- (0..n) [AbsensiKegiatan] (pengguna_id)
[JenisKegiatan]     -- (1..1) Memiliki  -- (0..n) [AbsensiKegiatan] (jenis_kegiatan_id)
[JenisKegiatan]     -- (1..1) Memiliki  -- (0..n) [TargetKegiatan] (jenis_kegiatan_id)
[User]              -- (1..1) Menetapkan-- (0..n) [TargetKegiatan] (ditetapkan_oleh)
[User]              -- (1..1) Menerima  -- (0..n) [Notifikasi] (pengguna_id)
```

---

## ─── 2. LOGICAL DATA MODEL (LDM) ───
LDM memperjelas struktur data dengan menempatkan kunci relasi (`FK`) ke dalam entitas-entitas yang membutuhkannya secara logis.

```text
┌──────────────────────────────────────────────────────────────────────────────────┐
│  TABEL LOGIS DAN ATRIBUT KUNCI                                                    │
├──────────────────────────────────────────────────────────────────────────────────┤
│  1. User (id [PK], mahallah_id [FK], ...)                                        │
│  2. Wilayah (id [PK], pengurus_id [FK], ...)                                     │
│  3. Mahallah (id [PK], wilayah_id [FK], ...)                                     │
│  4. PendaftaranWajah (id [PK], pengguna_id [FK], ...)                            │
│  5. JadwalItikaf (id [PK], dibuat_oleh [FK], ...)                                │
│  6. PesertaItikaf (id [PK], jadwal_itikaf_id [FK], pengguna_id [FK], ...)        │
│  7. LaporanItikaf (id [PK], jadwal_itikaf_id [FK], amir_id [FK], ...)            │
│  8. BerkasLaporan (id [PK], laporan_itikaf_id [FK], ...)                         │
│  9. AbsensiItikaf (id [PK], jadwal_itikaf_id [FK], pengguna_id [FK], ...)        │
│ 10. JenisKegiatan (id [PK], ...)                                                 │
│ 11. AbsensiKegiatan (id [PK], pengguna_id [FK], jenis_kegiatan_id [FK], ...)     │
│ 12. TargetKegiatan (id [PK], jenis_kegiatan_id [FK], ditetapkan_oleh [FK], ...)   │
│ 13. Notifikasi (id [PK], pengguna_id [FK], ...)                                  │
└──────────────────────────────────────────────────────────────────────────────────┘
```

---

## ─── 3. PHYSICAL DATA MODEL (PDM) ───
PDM mendefinisikan detail fisik tabel pada database nyata (MySQL), lengkap dengan tipe data fisik, batas panjang karakter, serta status kolom (`NULL` / `NOT NULL`).

### 1. Tabel `users` (Pengguna)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `name` | VARCHAR(255) | No | |
| `email` | VARCHAR(255) | No (Unique) | |
| `password` | VARCHAR(255) | No | |
| `no_telepon` | VARCHAR(20) | Yes | |
| `jenis_kelamin` | CHAR(1) | Yes | |
| `tanggal_lahir` | DATE | Yes | |
| `role` | VARCHAR(20) | No | |
| `foto_profil` | VARCHAR(255) | Yes | |
| `fcm_token` | VARCHAR(255) | Yes | |
| `status` | VARCHAR(20) | No | |
| `mahallah_id` | INT | Yes (FK) | Hubung ke `mahallahs.id` (ON DELETE SET NULL) |

### 2. Tabel `wilayahs` (Wilayah Kepengurusan)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `nama_wilayah` | VARCHAR(100) | No | |
| `deskripsi` | VARCHAR(255) | Yes | |
| `status` | VARCHAR(20) | No | |
| `pengurus_id` | INT | Yes (FK) | Hubung ke `users.id` (ON DELETE SET NULL) |

### 3. Tabel `mahallahs` (Masjid/Mihrab)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `nama_mahallah` | VARCHAR(100) | No | |
| `alamat` | VARCHAR(255) | Yes | |
| `latitude` | DOUBLE | Yes | |
| `longitude` | DOUBLE | Yes | |
| `status` | VARCHAR(20) | No | |
| `wilayah_id` | INT | No (FK) | Hubung ke `wilayahs.id` (ON DELETE CASCADE) |

### 4. Tabel `pendaftaran_wajahs` (Verifikasi Biometrik)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `aws_face_id` | CHAR(36) | No | |
| `aws_collection_id` | VARCHAR(100) | No | |
| `status` | VARCHAR(20) | No | |
| `terdaftar_pada` | DATETIME | No | |
| `pengguna_id` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |

### 5. Tabel `jadwal_itikafs` (Jadwal Kegiatan)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `nama_itikaf` | VARCHAR(100) | No | |
| `tanggal_mulai` | DATE | No | |
| `tanggal_selesai` | DATE | No | |
| `nama_lokasi` | VARCHAR(255) | No | |
| `latitude` | DOUBLE | Yes | |
| `longitude` | DOUBLE | Yes | |
| `radius_meter` | INT | No | |
| `keterangan` | VARCHAR(255) | Yes | |
| `status` | VARCHAR(20) | No | |
| `dibuat_oleh` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |

### 6. Tabel `peserta_itikafs` (Mapping Peserta & Amir)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `adalah_amir` | TINYINT(1) | No | |
| `jadwal_itikaf_id` | INT | No (FK) | Hubung ke `jadwal_itikafs.id` (ON DELETE CASCADE) |
| `pengguna_id` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |
| `dipilih_oleh` | INT | Yes (FK) | Hubung ke `users.id` (ON DELETE SET NULL) |

### 7. Tabel `laporan_itikafs` (Laporan Pelaksanaan Sesi)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `nama_sesi` | VARCHAR(100) | No | |
| `waktu_mulai` | DATETIME | No | |
| `waktu_selesai` | DATETIME | No | |
| `uraian_kegiatan` | VARCHAR(255) | No | |
| `peserta_hadir` | VARCHAR(255) | Yes | |
| `status` | VARCHAR(20) | No | |
| `catatan_wilayah` | VARCHAR(255) | Yes | |
| `catatan_inti` | VARCHAR(255) | Yes | |
| `dikirim_pada` | DATETIME | Yes | |
| `disetujui_pada` | DATETIME | Yes | |
| `dokumen_pendukung` | VARCHAR(255) | Yes | |
| `jadwal_itikaf_id` | INT | No (FK) | Hubung ke `jadwal_itikafs.id` (ON DELETE CASCADE) |
| `amir_id` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |

### 8. Tabel `berkas_laporans` (Lampiran Bukti Foto/PDF)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `nama_berkas` | VARCHAR(255) | No | |
| `path_s3` | VARCHAR(255) | No | |
| `tipe_berkas` | VARCHAR(50) | No | |
| `ukuran_berkas` | INT | Yes | |
| `laporan_itikaf_id` | INT | No (FK) | Hubung ke `laporan_itikafs.id` (ON DELETE CASCADE) |

### 9. Tabel `absensi_itikafs` (Log Absensi Wajah & GPS)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `waktu_absen` | DATETIME | No | |
| `latitude_aktual` | DOUBLE | Yes | |
| `longitude_aktual` | DOUBLE | Yes | |
| `jarak_meter` | DOUBLE | Yes | |
| `status_gps` | TINYINT(1) | Yes | |
| `status_wajah` | TINYINT(1) | Yes | |
| `status_absen` | VARCHAR(20) | No | |
| `keterangan_gagal` | VARCHAR(255) | Yes | |
| `jadwal_itikaf_id` | INT | No (FK) | Hubung ke `jadwal_itikafs.id` (ON DELETE CASCADE) |
| `pengguna_id` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |

### 10. Tabel `jenis_kegiatans` (Master Data Kegiatan Mandiri)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `nama_kegiatan` | VARCHAR(100) | No | |
| `deskripsi` | VARCHAR(255) | Yes | |
| `status` | VARCHAR(20) | No | |

### 11. Tabel `absensi_kegiatans` (Log Progres Ibadah Mandiri)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `waktu_kegiatan` | DATETIME | No | |
| `status_wajah` | TINYINT(1) | Yes | |
| `status_absen` | VARCHAR(20) | No | |
| `pengguna_id` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |
| `jenis_kegiatan_id` | INT | No (FK) | Hubung ke `jenis_kegiatans.id` (ON DELETE CASCADE) |

### 12. Tabel `target_kegiatans` (Patokan Target Ibadah)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `jumlah_target` | INT | No | |
| `periode` | VARCHAR(20) | No | |
| `tahun` | INT | No | |
| `bulan` | INT | Yes | |
| `jenis_kegiatan_id` | INT | No (FK) | Hubung ke `jenis_kegiatans.id` (ON DELETE CASCADE) |
| `ditetapkan_oleh` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |

### 13. Tabel `notifikasis` (Push Notifikasi)
| Nama Kolom | Tipe Data Fisik | Nullable? | Relasi Key (Constraint) |
| :--- | :--- | :--- | :--- |
| `id` | INT AUTO_INCREMENT | No (PK) | |
| `judul` | VARCHAR(100) | No | |
| `pesan` | VARCHAR(255) | No | |
| `tipe` | VARCHAR(50) | Yes | |
| `referensi_id` | INT | Yes | |
| `referensi_tipe` | VARCHAR(100) | Yes | |
| `dibaca` | TINYINT(1) | No (Default: 0) | |
| `dibaca_pada` | DATETIME | Yes | |
| `pengguna_id` | INT | No (FK) | Hubung ke `users.id` (ON DELETE CASCADE) |
