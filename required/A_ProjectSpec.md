# FinalCut — Project Specification
**Tingkat kepentingan: A (paling krusial, jangan diabaikan)**

## 1. Ringkasan Proyek
- **Nama Proyek:** FinalCut
- **Jenis:** Tugas mata kuliah pemrograman web (individu)
- **Deadline:** Akhir September 2026 (±9 minggu sejak 28 Juli 2026)
- **Satu kalimat:** FinalCut adalah platform web yang menggabungkan database & ulasan film (ala Letterboxd) dengan pemesanan tiket bioskop (ala Tix.id) dalam satu aplikasi.

## 2. Masalah yang Diselesaikan
Saat ini pengguna harus berpindah platform: buka Letterboxd/IMDb untuk cari ulasan film, lalu pindah ke aplikasi bioskop terpisah (Tix.id, dsb.) untuk beli tiket. FinalCut menyatukan riset film + booking tiket dalam satu alur, tanpa perlu app-switching.

## 3. Tech Stack (WAJIB, jangan diganti tanpa alasan kuat)
| Layer | Teknologi | Catatan |
|---|---|---|
| Backend | Laravel (PHP) | Routing, Eloquent ORM, middleware |
| Frontend/Template | Blade | **Bukan React.** Sudah diputuskan sadar karena keterbatasan waktu 9 minggu dan belum ada pengalaman React sebelumnya |
| Styling | Tailwind CSS | |
| Database | MySQL | nama database: `db_finalcut` |
| Auth | Laravel Breeze | login, register, middleware `auth` |
| PDF/Export | DomPDF (`barryvdh/laravel-dompdf`) | untuk cetak laporan |
| Local server | Laragon / XAMPP | |

> **Catatan untuk AI agent:** JANGAN menyarankan atau menambahkan React, Vue, atau framework frontend JS lain kecuali diminta eksplisit oleh user. Proyek ini sengaja server-rendered dengan Blade demi kesederhanaan dalam waktu pengerjaan terbatas.

## 4. Persona Pengguna
1. **User/Penonton** — mendaftar, cari & ulas film, booking tiket, kelola watchlist & diary tontonan
2. **Admin** — kelola data master (film, bioskop, studio, jadwal), pantau transaksi, cetak laporan, kelola user

## 5. Kriteria Penilaian (dari dosen — semua poin ini WAJIB terpenuhi)
- **Fitur Utama:** CRUD data utama (Tambah, Edit, Hapus, Tampil), pencarian data, semua tombol berfungsi tanpa error
- **Sistem Role & Keamanan:** role (admin/user), pembatasan akses, middleware
- **Testing & Debugging:** cegah SQL Injection, tidak ada error, pengujian fitur sudah berjalan
- **Cetak Data/Laporan:** uji login dengan akun berbeda, data transaksi, rekapitulasi

## 6. Ruang Lingkup

### Termasuk (in-scope)
- 17 halaman (10 sisi user, 7 sisi admin) — lihat `B_Concept.md`
- CRUD penuh untuk film, bioskop, studio, jadwal tayang
- Sistem ulasan & rating (1–5 bintang)
- Watchlist & diary tontonan (fitur ala Letterboxd)
- Alur booking lengkap: pilih bioskop → jadwal → kursi → pembayaran
- Laporan transaksi & rekapitulasi (export PDF)
- Role-based middleware (admin vs user)

### Tidak termasuk (out-of-scope — JANGAN dikerjakan kecuali diminta eksplisit)
- Payment gateway sungguhan (cukup simulasi status pembayaran di database)
- Aplikasi mobile
- Notifikasi real-time / push notification
- Multi-bahasa (i18n)
- React atau frontend framework JS apa pun

## 7. Definisi Selesai (Definition of Done)
- [ ] Semua 17 halaman bisa diakses sesuai role masing-masing
- [ ] Semua tombol/form berfungsi tanpa error
- [ ] Middleware mencegah user biasa mengakses halaman admin
- [ ] Semua query database pakai Eloquent ORM (tidak ada raw SQL rentan injection)
- [ ] Laporan bisa di-export ke PDF
- [ ] Sudah diuji dengan minimal 2 akun berbeda (admin & user)

## 8. Referensi File Lain
- Roadmap pengerjaan → `A_Roadmap.md`
- Konsep produk, design system, daftar halaman, skema database → `B_Concept.md`
- Alur pengguna & sistem → `B_Flow.md`
- Aturan bisnis/data → `C_BusinessLogic.md`
- Konvensi kode → `C_Rules.md`
