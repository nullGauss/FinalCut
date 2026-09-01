# FinalCut — Concept & Design System
**Tingkat kepentingan: B (penting, secondary terhadap spec & roadmap)**

## 1. Konsep Produk
FinalCut = **Letterboxd** (database film + ulasan komunitas) + **Tix.id** (booking tiket bioskop), digabung jadi satu platform.

Dua pilar utama:
1. **Cinephile pillar** — cari film, baca/tulis ulasan, rating, watchlist, diary tontonan
2. **Booking pillar** — lihat jadwal tayang di bioskop terdekat, pilih kursi, bayar, dapat e-tiket

## 2. Identitas & Nama
- **Nama:** FinalCut (istilah editing film: potongan final)
- **Tone:** santai, komunitas cinephile Indonesia muda, tidak formal-korporat

## 3. Design System (sudah difinalisasi & disetujui user — jangan diubah tanpa izin)
Estetika: **indie/emo/lo-fi zine** — terinspirasi visual indie band (mis. Helicopter Homework) dan doodle manga line-art.

| Token | Nilai |
|---|---|
| Background | `#F7F7F7` |
| Surface/Card | `#FFFFFF` |
| Ink (teks utama & border) | `#111111` |
| Teks sekunder | `#444444` |
| Aksen biru (tag) | bg `#DCE9F5`, teks `#1F4E79` |
| Aksen kuning (tag/highlight) | bg `#FBEBA0`, teks `#6B5900` |
| Border | 1.5px solid ink |
| Shadow signature | `3px 3px 0 #111111` (hard shadow, bukan blur) |
| Font display/heading | Space Grotesk (600/700) |
| Font body | Inter (400/500/600) |
| Font aksen tulisan tangan | Indie Flower (untuk judul informal, mis. "ulasan populer dari sesama penonton~") |

Elemen khas: obi-strip (label vertikal ala sampul buku Jepang), hard shadow, border tipis solid hitam, poster placeholder pola garis diagonal, tombol pill dengan border hitam.

## 4. Persona
### User/Penonton
Anak muda urban, suka nonton bioskop & drama/film lokal, aktif di media sosial, terbiasa pakai app booking dan platform review film.

### Admin
Pengelola sistem (mis. staf bioskop/operator platform) — butuh akses cepat untuk kelola data film, jadwal, dan melihat laporan transaksi.

## 5. Inventaris 17 Halaman

### Sisi User (10 halaman)
| # | Halaman | Fungsi Singkat |
|---|---|---|
| 1 | Login | Autentikasi, redirect sesuai role |
| 2 | Register | Daftar akun baru (role default: user) |
| 3 | Dashboard | Hub utama: spotlight film, statistik, sedang tayang, koleksi, riwayat, ulasan populer |
| 4 | Daftar Film | Browse & cari semua film, filter genre |
| 5 | Detail Film | Sinopsis, cast, trailer, rating, ulasan, tombol booking & watchlist |
| 6 | Booking – Pilih Bioskop & Jadwal | Pilih bioskop terdekat + jam tayang |
| 7 | Booking – Pilih Kursi & Pembayaran | Seat map + checkout |
| 8 | Riwayat Transaksi | Semua booking milik user, status transaksi |
| 9 | Koleksi Saya | Tab: Watchlist (mau ditonton) & Diary (sudah ditonton) |
| 10 | Profile | Lihat & edit data akun (edit profile ada di dalam halaman ini, bukan halaman terpisah) |

### Sisi Admin (7 halaman)
| # | Halaman | Fungsi Singkat |
|---|---|---|
| 11 | Dashboard Admin | Ringkasan sistem: total user, transaksi, film terlaris |
| 12 | Kelola Film | CRUD film (modal tambah/edit di dalam satu halaman) |
| 13 | Kelola Bioskop & Studio | CRUD bioskop dan studio di dalamnya |
| 14 | Kelola Jadwal Tayang | CRUD showtime (film, studio, jam, harga) |
| 15 | Kelola Transaksi | Lihat semua booking dari semua user |
| 16 | Laporan & Rekapitulasi | Grafik pendapatan, film terlaris, export PDF |
| 17 | Kelola User | Kelola role & status akun |

> **Catatan pola UI:** halaman "Kelola..." di admin memakai pola **list + modal** (bukan halaman terpisah untuk tambah/edit) supaya jumlah halaman tetap ramping — 17 halaman, bukan 33 seperti rancangan awal yang belum dirampingkan.

## 6. Skema Database (ringkas — 14 tabel, database `db_finalcut`)
`users`, `genres`, `movies`, `movie_genre` (pivot), `reviews`, `watchlist`, `watched_diary`, `cinemas`, `studios`, `showtimes`, `seats`, `bookings`, `payments`, `booking_seats` (pivot)

Detail kolom, tipe data, dan relasi lengkap ada di file `db_finalcut.sql` pada root project.
