# FinalCut — Roadmap Pengerjaan (9 Minggu)
**Tingkat kepentingan: A (paling krusial, jangan diabaikan)**

> Total waktu: 28 Juli 2026 – akhir September 2026. Disusun berdasarkan keputusan memakai Laravel + Blade (bukan React) demi mengejar deadline. Lihat `A_ProjectSpec.md` untuk konteks tech stack.

## Minggu 1 (28 Jul – 3 Ags) — Fondasi
- [ ] Setup project Laravel baru
- [ ] Buat database `db_finalcut`, jalankan migration untuk 14 tabel (lihat `B_Concept.md`)
- [ ] Install & konfigurasi Laravel Breeze (login, register)
- [ ] Buat middleware role (`admin`, `user`)
- [ ] Setup Tailwind CSS

**Target akhir minggu:** User bisa register/login dan diarahkan ke dashboard sesuai role.

## Minggu 2–3 (4–17 Ags) — Modul Film
- [ ] CRUD film (admin): tambah/edit/hapus/tampil
- [ ] Halaman Daftar Film (browse, search, filter genre)
- [ ] Halaman Detail Film (sinopsis, cast, trailer, rating rata-rata)
- [ ] Sistem ulasan & rating (1–5 bintang) oleh user
- [ ] Fitur genre (tabel pivot `movie_genre`)

**Target akhir:** User bisa cari film, lihat detail, dan menulis ulasan. Admin bisa CRUD film penuh.

## Minggu 4–5 (18–31 Ags) — Modul Booking
- [ ] Halaman pilih bioskop & jadwal tayang (showtimes)
- [ ] Halaman pilih kursi (seat map dari tabel `seats`)
- [ ] Halaman checkout/pembayaran (booking + payment)
- [ ] Update status booking (pending/paid/cancelled)
- [ ] Halaman riwayat transaksi (user)

**Target akhir:** Alur booking lengkap dari pilih film sampai dapat status transaksi, berjalan tanpa error.

## Minggu 6 (1–7 Sep) — Dashboard Admin
- [ ] Kelola Bioskop & Studio
- [ ] Kelola Jadwal Tayang
- [ ] Kelola Transaksi (lihat semua booking dari semua user)
- [ ] Laporan & Rekapitulasi (grafik pendapatan, export PDF)
- [ ] Kelola User (ubah role, nonaktifkan akun)

**Target akhir:** Semua 7 halaman admin selesai dan berfungsi.

## Minggu 7 (8–14 Sep) — Koleksi & Profile
- [ ] Watchlist (tabel `watchlist`)
- [ ] Diary tontonan (tabel `watched_diary`)
- [ ] Halaman "Koleksi Saya" (gabungan watchlist + diary, pakai tab)
- [ ] Halaman Profile & Edit Profile

**Target akhir:** Fitur pembeda ala Letterboxd selesai — ini yang membuat FinalCut berbeda dari aplikasi booking biasa.

## Minggu 8 (15–21 Sep) — Testing & Debugging
- [ ] Cek semua query pakai Eloquent (bukan raw SQL) → cegah SQL Injection
- [ ] Uji semua tombol/form di 17 halaman, catat & perbaiki bug
- [ ] Uji login dengan minimal 2 akun berbeda (admin & user), pastikan middleware bekerja
- [ ] Uji cetak laporan (PDF)

**Target akhir:** Tidak ada error tersisa, semua kriteria penilaian di `A_ProjectSpec.md` terpenuhi.

## Minggu 9 (22–30 Sep) — Buffer & Polish
- [ ] Perbaikan bug dari testing minggu 8
- [ ] Polish tampilan (konsistensi desain, lihat `B_Concept.md`)
- [ ] Siapkan bahan presentasi/demo
- [ ] Deploy / persiapan submit

---
**Catatan untuk AI agent:** Kalau user meminta bantuan mengerjakan sebuah fitur, cek dulu fitur itu masuk minggu ke berapa di roadmap ini — supaya prioritas kerja tetap sesuai jalur dan tidak melenceng dari deadline.
