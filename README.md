# FindIt FASILKOM — Sistem Layanan Lost & Found Terintegrasi
### Fakultas Ilmu Komputer &bull; Universitas Singaperbangsa Karawang (UNSIKA)

[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.x-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tests Passing](https://img.shields.io/badge/Tests-9%20passed%20%7C%2069%20assertions-success)](https://phpunit.de)

---

## 1. Deskripsi Proyek

**FindIt FASILKOM** adalah platform digital Lost & Found resmi Fakultas Ilmu Komputer, Universitas Singaperbangsa Karawang. Sistem ini memodernisasi pencatatan konvensional berbasis buku pos keamanan menjadi layanan digital terpusat yang aman, tertib, dan transparan bagi seluruh civitas akademika (mahasiswa, dosen, staf) serta petugas pos pengamanan kampus.

### Prinsip Operasional Utama
> **"Digital untuk Informasi. Fisik untuk Serah Terima."**
> 
> Seluruh alur pelaporan, pencarian, dan perbandingan ciri bukti dilakukan secara daring (digital). Namun, verifikasi fisik identitas (KTM/KTP) dan penyerahan barang wajib dilakukan langsung di **Pos Keamanan Gedung FASILKOM Lantai 1** untuk menjamin akuntabilitas serah terima barang.

---

## 2. Fitur Utama Sistem

### A. Peran Civitas Akademika (Mahasiswa / Pelapor)
- **Katalog Barang Temuan Publik:** Menampilkan barang temuan yang telah diamankan petugas dengan foto asli beresolusi tinggi, lokasi, tanggal, dan status barang.
- **Perlindungan Privasi Ciri Khusus:** Ciri sensitif (isi dompet, nomor seri, goresan khas) sengaja disembunyikan di katalog publik untuk memitigasi klaim palsu.
- **Pelaporan Kehilangan Barang:** Formulir terstruktur mencakup kategori, kronologi, ciri khusus pribadi, lokasi terakhir terlihat, kontak WhatsApp, dan unggah foto opsional.
- **Pelaporan Temuan oleh Civitas (Alur D):** Fasilitas bagi mahasiswa/dosen yang menemukan barang di lingkungan kampus untuk mendata barang temuan sebelum menyerahkannya secara fisik ke Pos Security.
- **Pengajuan Klaim Kepemilikan:** Formulir pengajuan klaim mencakup kronologi kehilangan, deskripsi ciri rahasia kepemilikan, dan unggahan foto bukti kepemilikan privat.
- **Pusat Pemantauan & Riwayat:** Dashboard mahasiswa untuk melacak status laporan kehilangan, status verifikasi klaim, linimasa progres, dan notifikasi pencocokan barang.

### B. Peran Petugas Pos Keamanan (Security)
- **Dashboard Operasional & KPI:** Monitoring real-time total inventaris barang, barang tersedia, klaim menunggu verifikasi, laporan aktif masuk, dan barang yang berhasil dikembalikan.
- **Visualisasi Statistik 7 Hari Terakhir:** Bar chart proporsional dinamis yang membandingkan volume barang masuk vs barang keluar serah terima.
- **Manajemen Inventaris Barang Temuan:** Pendataan kode inventaris otomatis (`FND-YYYY-XXXX`), nomor rak lemari penyimpanan, deskripsi publik, deskripsi privat pengamanan, dan unggah foto asli.
- **Verifikasi Side-by-Side Klaim:** Antarmuka komparasi berdampingan antara ciri fisik barang di inventaris security dengan bukti kepemilikan pribadi pemohon klaim.
- **Persetujuan & Berita Acara Serah Terima:** Otorisasi klaim (`Pending` $\rightarrow$ `Approved` / `Rejected`) dilanjutkan dengan modul serah terima fisik pos security lengkap dengan pencatatan identitas pengambil dan tanda terima cetak (*receipt*).
- **Penerimaan Temuan Civitas (Alur D):** Konfirmasi penyerahan fisik barang temuan dari mahasiswa dan konversi otomatis menjadi inventaris resmi security.

---

## 3. Tech Stack & Arsitektur

- **Framework Backend:** Laravel 13 (PHP 8.2+)
- **Templating Frontend:** Laravel Blade
- **Styling UI:** Tailwind CSS v4 + Vite + Inter Font
- **Basis Data:** MySQL (`findit_fasilkom`)
- **Autentikasi & Otorisasi:** Laravel Auth dengan Middleware Role Protection (`role:petugas` dan `role:mahasiswa`)
- **Penyimpanan Berkas:** Laravel Storage Symlink (`storage/app/public` $\rightarrow$ `public/storage`)
- **Testing:** PHPUnit Feature Test Suite dengan Database Transactions

---

## 4. Panduan Menjalankan Aplikasi Secara Lokal

### Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL Server (misal melalui Laragon, XAMPP, atau instalasi native)

### Langkah Instalasi

1. **Clone repositori dan masuk ke direktori proyek:**
   ```bash
   cd c:\Projects\FindIt
   ```

2. **Pasang dependensi PHP dan Node.js:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`):**
   Pastikan pengaturan basis data pada berkas `.env` sesuai dengan konfigurasi lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=findit_fasilkom
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key & Symlink Storage:**
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```

5. **Jalankan Migrasi & Seeder Database (dengan Foto Asli):**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Perintah ini akan membuat skema tabel dan mengisi basis data dengan 12 data barang temuan berfoto asli, kategori resmi, pengguna uji, laporan kehilangan, pengajuan klaim, serta notifikasi.*

6. **Kompilasi Aset Frontend (Tailwind CSS v4 & Vite):**
   ```bash
   npm run build
   ```

7. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui peramban web pada: **`http://127.0.0.1:8000`**

---

## 5. Akun Bawaan untuk Pengujian (Default Credentials)

| Peran | Nama Pengguna | Email | Kata Sandi | Deskripsi |
|---|---|---|---|---|
| **Mahasiswa** | Nadia Putri | `nadia.putri@example.ac.id` | `password` | Mahasiswa S1 SI (NPM: 2210631170042) dengan laporan dompet aktif dan klaim kepemilikan. |
| **Petugas** | Dedi Saputra | `petugas@findit.ac.id` | `password` | Petugas Pos Keamanan FASILKOM (ID: SEC-FASILKOM-01) berhak mengakses seluruh panel petugas. |

---

## 6. Struktur Basis Data Utama

1. `users`: Tabel pengguna dengan penanda `role` (`mahasiswa` / `petugas`), NIM/NIP, program studi/departemen, dan nomor kontak.
2. `categories`: Kategori barang temuan (*Elektronik & Gadget*, *Aksesori & Dompet*, *Dokumen & Kartu Identitas*, *Pakaian & Perlengkapan*, *Kunci & Kendaraan*, *Lain-lain*).
3. `found_items`: Inventaris barang temuan pos security dengan pemisahan atribut `public_description` dan `private_description` untuk menjaga keamanan verifikasi, lokasi rak fisik, serta referensi serah terima.
4. `lost_reports`: Laporan kehilangan dari mahasiswa dengan status linimasa (`menunggu`, `diproses`, `cocok`, `selesai`, `dibatalkan`).
5. `claims`: Pengajuan klaim kepemilikan atas barang tertentu dengan status (`pending`, `approved`, `rejected`, `completed`), bukti foto privat, catatan verifikasi, dan data serah terima fisik pos security.
6. `civitas_found_reports`: Laporan barang temuan langsung oleh civitas kampus (Alur D) sebelum penyerahan fisik ke pos keamanan.
7. `notifications`: Notifikasi real-time untuk pembaruan status klaim dan kecocokan laporan kehilangan.

---

## 7. Eksekusi Pengujian Otomatis

Aplikasi dilengkapi dengan rangkaian Feature Test komprehensif pada `tests/Feature/FindItApplicationTest.php`:
```bash
php artisan test
```

Cakupan pengujian mencakup:
- Pemeriksaan kelayakan muat halaman publik (Landing Page & Katalog).
- Verifikasi pembatasan otorisasi peran (mahasiswa dicegah membuka panel petugas).
- Akses penuh petugas terhadap seluruh modul pos keamanan.
- Siklus hidup pembuatan laporan kehilangan oleh mahasiswa.
- Siklus pengajuan klaim kepemilikan terhadap barang temuan.
- Verifikasi klaim oleh petugas (persetujuan/penolakan catatan security).
- Alur D pelaporan barang temuan civitas kampus.
- Penyaringan pencarian katalog berdasarkan kata kunci dan filter kategori.
- Validasi data masukan formulir pelaporan.
