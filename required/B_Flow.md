# FinalCut — User & System Flow
**Tingkat kepentingan: B (penting, secondary terhadap spec & roadmap)**

## 1. Flow Autentikasi (paling krusial — semua flow lain bergantung ke sini)
```
[Buka web] → [Login / Register]
                    |
        Register (user baru, role default: user)
                    |
                    v
            [Cek kredensial]
                    |
        +-----------+-----------+
        |                       |
   role = admin            role = user
        |                       |
        v                       v
[Dashboard Admin]        [Dashboard User]
```
- Middleware `auth`: wajib login untuk akses halaman manapun selain Login/Register
- Middleware `role`/`is_admin`: cek role sebelum masuk rute `/admin/*`
- Kalau user biasa mencoba akses rute admin → redirect / tampilkan 403

## 2. Flow Utama User: Cari Film → Booking
```
Dashboard -> Daftar Film (search/filter)
    -> Detail Film
        -> Tulis ulasan/rating
        -> Tambah ke Watchlist
        -> Booking tiket
              -> Pilih Bioskop & Jadwal
              -> Pilih Kursi
              -> Pembayaran (checkout)
              -> Status booking: pending -> paid / cancelled
              -> Muncul di Riwayat Transaksi
```

## 3. Flow Koleksi (fitur ala Letterboxd)
```
Detail Film -> tombol "Tambah ke Watchlist" -> masuk tabel `watchlist`
Detail Film -> tombol "Tandai sudah ditonton" -> isi tanggal -> masuk tabel `watched_diary`
Koleksi Saya -> tab Watchlist / tab Diary -> tampilkan data dari 2 tabel di atas
```

## 4. Flow Admin: Kelola Data
```
Dashboard Admin
    -> Kelola Film        -> modal Tambah/Edit -> simpan ke `movies` (+ `movie_genre`)
    -> Kelola Bioskop     -> modal Tambah/Edit -> simpan ke `cinemas`, `studios`
    -> Kelola Jadwal      -> modal Tambah/Edit -> simpan ke `showtimes`
    -> Kelola Transaksi   -> lihat semua `bookings` + `payments`, filter status
    -> Laporan            -> agregasi dari `bookings`/`payments` -> export PDF
    -> Kelola User        -> ubah kolom `role` di tabel `users`
```

## 5. Flow Pembatasan Akses (middleware)
| Rute | Middleware | Yang boleh akses |
|---|---|---|
| `/login`, `/register` | — (guest only) | Belum login |
| `/dashboard`, `/movies/*`, `/booking/*`, `/collection`, `/profile` | `auth` | User & Admin yang sudah login |
| `/admin/*` | `auth`, `role:admin` | Hanya admin |
| Edit/hapus ulasan | `auth` + cek `user_id` di controller | Hanya pemilik ulasan sendiri |

> **Catatan untuk AI agent:** setiap kali membuat route baru, WAJIB tempelkan middleware sesuai tabel di atas. Ini poin penilaian eksplisit dari dosen ("Pembatasan akses", "Middleware") — lihat `A_ProjectSpec.md` bagian kriteria penilaian.
