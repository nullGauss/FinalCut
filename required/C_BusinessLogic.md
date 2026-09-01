# FinalCut — Business Logic & Rules
**Tingkat kepentingan: C (pelengkap teknis dari spec/flow) — tetap wajib diikuti supaya data konsisten**

## 1. Booking & Kursi
- Satu kursi (`seats`) hanya boleh dipesan oleh satu booking aktif pada satu `showtime` yang sama — cek dulu apakah kursi sudah ada di `booking_seats` untuk showtime tersebut sebelum membuat booking baru
- Status booking: `pending` (baru dibuat, belum bayar) → `paid` (lunas) atau `cancelled` (dibatalkan/kedaluwarsa)
- Kalau status jadi `cancelled`, kursi otomatis kembali tersedia untuk dipesan orang lain
- `total_price` di tabel `bookings` = harga per kursi (`showtimes.price`) × jumlah kursi yang dipesan

## 2. Ulasan & Rating
- Rating wajib antara 1–5 (sudah ada `CHECK` constraint di database)
- Rata-rata rating film = `AVG(rating)` dari semua `reviews` milik `movie_id` tersebut, dihitung on-the-fly (tidak perlu kolom cache kecuali performa jadi masalah nyata)
- Satu user sebaiknya hanya boleh menulis **satu** ulasan per film — tambahkan validasi di controller (atau constraint unique `user_id` + `movie_id`) supaya tidak dobel

## 3. Watchlist vs Diary — perbedaan penting
| | Watchlist | Diary (`watched_diary`) |
|---|---|---|
| Makna | "Mau ditonton nanti" | "Sudah ditonton" |
| Wajib tanggal? | Tidak | Ya (`watched_date`) |
| Satu film boleh ada di keduanya? | Boleh (mau nonton ulang meski sudah pernah) | — |
| Aksi trigger | Tombol "+ Watchlist" di Detail Film | Tombol "Tandai sudah ditonton" + isi tanggal |

## 4. Genre (relasi many-to-many)
- Satu film boleh punya lebih dari satu genre → pakai tabel pivot `movie_genre`
- Saat admin tambah/edit film, form harus multi-select genre, bukan single dropdown

## 5. Pembayaran (simulasi, bukan payment gateway asli)
- `payments.status`: `pending` → `success` / `failed`
- Untuk keperluan tugas, cukup simulasikan dengan tombol "Konfirmasi Pembayaran" yang mengubah status secara manual
- **Tidak perlu integrasi Midtrans/Xendit sungguhan** kecuali user secara eksplisit meminta itu dikerjakan (lihat `A_ProjectSpec.md` bagian out-of-scope)

## 6. Laporan & Rekapitulasi (untuk admin)
- Pendapatan per periode = `SUM(bookings.total_price)` WHERE `status = 'paid'`, dikelompokkan per tanggal/bulan
- Film terlaris = `COUNT(bookings)` per `movie_id` (lewat relasi `showtime -> movie`), diurutkan descending
- Export ke PDF pakai DomPDF (`barryvdh/laravel-dompdf`), bukan generate manual HTML-to-print

## 7. Validasi Umum (berlaku di semua form)
- Semua input wajib divalidasi di sisi server (Laravel Form Request atau `$request->validate()`), jangan hanya andalkan validasi HTML/JS di frontend
- Semua query WAJIB lewat Eloquent ORM/query builder — dilarang keras raw query dengan string concatenation dari input user (poin penilaian eksplisit "Cegah SQL Injection")
