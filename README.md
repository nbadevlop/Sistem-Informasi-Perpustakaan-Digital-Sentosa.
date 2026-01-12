# Sistem Informasi Perpustakaan Digital "Sentosa" 📚

**Sistem Informasi Perpustakaan Digital Sentosa** adalah aplikasi berbasis web modern yang dirancang untuk mendigitalisasi seluruh proses operasional perpustakaan. Aplikasi ini mengubah metode manual yang lambat menjadi sistem otomatis yang cepat, akurat, dan efisien.

Dibangun menggunakan framework **Laravel** yang handal dan antarmuka **Tailwind CSS** yang responsif, aplikasi ini memfasilitasi manajemen data buku, anggota, sirkulasi peminjaman, hingga pelaporan denda secara *real-time*.

## 🚀 Fitur Unggulan

Fokus utama aplikasi ini adalah kecepatan pelayanan melalui dukungan teknologi **Barcode Scanner** dan kemudahan akses informasi bagi pemustaka.

### 1. Modul Sirkulasi (Transaksi Cepat)
Jantung dari aplikasi yang dirancang untuk kecepatan tinggi:
- **Peminjaman Estafet (Scan-to-Borrow):** Alur kerja otomatis tanpa ketik manual. Admin Scan Kartu Anggota ➝ Kursor pindah otomatis ➝ Admin Scan Label Buku ➝ Data tersimpan.
- **Pengembalian Kilat (Quick Return):** Fitur *"One Scan Return"*. Cukup scan barcode buku, sistem otomatis mencari peminjam terakhir dan mengembalikan stok.
- **Kalkulator Denda Otomatis:** Menghitung denda secara otomatis jika pengembalian melebihi batas waktu (Default: 7 hari).

### 2. Manajemen Master Data
- **Data Buku & Stok:** Pencatatan detail buku lengkap dengan fitur cetak **Label QR Code** siap tempel.
- **Data Anggota:** Manajemen data mahasiswa dengan fitur cetak **Kartu Anggota** unik.
- **Keamanan Data (Soft Deletes):** Menghapus data induk (buku/mahasiswa) tidak akan menghilangkan riwayat transaksi historis.

### 3. Portal Publik (Landing Page)
Halaman depan yang dapat diakses mahasiswa/dosen tanpa login:
- **Live Search:** Pencarian buku super cepat (AJAX) tanpa reload halaman.
- **Cek Stok Real-time:** Indikator visual stok "Tersedia" (Hijau) atau "Habis" (Merah).
- **Katalog Terbaru:** Menampilkan koleksi buku terbaru.

### 4. Laporan & Keuangan
- **Laporan Denda:** Rekapitulasi uang denda masuk (Lunas) dan tertunggak (Piutang) dengan filter tanggal.
- **Export Dokumen:** Mendukung ekspor data ke **PDF** (siap cetak) dan **Excel** (olah data).

### 5. Antarmuka & Keamanan
- **Custom Login Page:** Tampilan login modern dengan latar belakang visual perpustakaan.
- **Responsive Sidebar:** Menu navigasi yang rapi dan responsif (Mobile Friendly).
- **Role System:** Akses terbatas hanya untuk Petugas/Admin terdaftar.

---

## 🛠️ Spesifikasi Teknis (Tech Stack)

Aplikasi ini dibangun menggunakan teknologi modern untuk menjamin performa dan kemudahan pengembangan:

| Komponen | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Backend** | Laravel (PHP) | Framework MVC yang aman dan robust. |
| **Database** | MySQL | Penyimpanan data relasional. |
| **Frontend** | Tailwind CSS | Desain antarmuka modern dan responsif. |
| **Interactivity** | jQuery & AJAX | *Live Search* dan *Scan Barcode* tanpa reload. |
| **Data Grid** | Yajra DataTables | Menangani ribuan data dengan *Server-side processing*. |
| **PDF Engine** | DomPDF | Generate laporan dan kartu anggota. |

---

## 🔄 Alur Kerja Sistem (Workflow)

1.  **Admin Login:** Masuk ke dashboard menggunakan akun petugas.
2.  **Input Data:** Admin input data buku baru & cetak label QR.
3.  **Peminjaman:** Mahasiswa membawa buku ➝ Admin Scan Kartu & Buku ➝ Selesai.
4.  **Pengembalian:** Mahasiswa kembalikan buku ➝ Admin Scan Buku ➝ Sistem validasi denda ➝ Stok kembali.
5.  **Pelaporan:** Admin mencetak laporan denda bulanan/harian.

---

## 🎯 Tujuan & Manfaat
* **Efisiensi Waktu:** Memangkas antrean dengan sistem scan estafet.
* **Akurasi Data:** Meminimalisir kesalahan stok dan hitungan denda.
* **Transparansi:** Mahasiswa bisa cek ketersediaan buku sendiri dari rumah.

---
*Dikembangkan untuk Perpustakaan Digital Sentosa © 2026*
