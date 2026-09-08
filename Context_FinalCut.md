# Context File — FinalCut
# Dokumen Konteks Lengkap untuk Penulisan Laporan Akhir Pembuatan Projek

> **Catatan untuk AI (Claude):** Dokumen ini adalah satu-satunya sumber kebenaran (Single Source of Truth) mengenai proyek FinalCut. Seluruh informasi teknis, fitur, alur kerja, struktur database, batasan masalah, dan arsitektur sistem yang Anda butuhkan untuk menulis laporan sudah tercantum di sini. Kembangkan setiap poin menjadi paragraf naratif yang deskriptif dan akademis. Jangan mengarang informasi di luar dokumen ini.

---

## BAGIAN 1: IDENTITAS PROYEK

### 1.1 Informasi Umum

| Field | Nilai |
|---|---|
| Nama Aplikasi | FinalCut |
| Sub-tema | Platform Ulasan Film & Pemesanan Tiket Bioskop Terintegrasi |
| Jenis Proyek | Tugas Akhir mata kuliah Pemrograman Web (individu) |
| Institusi | [NAMA SEKOLAH/INSTITUSI] |
| Program Keahlian | Rekayasa Perangkat Lunak |
| Nama Siswa | [NAMA LENGKAP SISWA] |
| NIS | [NIS] |
| Kelas | [KELAS] |
| Tahun Ajaran | 2026/2027 |
| Deadline | Akhir September 2026 (±9 minggu sejak 28 Juli 2026) |

### 1.2 Ringkasan Konsep

FinalCut adalah platform web yang menggabungkan dua konsep utama dalam satu aplikasi:

1. **Cinephile Pillar (Letterboxd-style):** Basis data film komunitas yang memungkinkan pengguna menulis ulasan, memberikan rating 1-5 bintang, mengelola watchlist (film yang ingin ditonton), dan diary tontonan (film yang sudah ditonton beserta tanggal menontonnya).
2. **Booking Pillar (Tix.id-style):** Sistem pemesanan tiket bioskop yang memungkinkan pengguna memilih bioskop, jadwal tayang, kursi, melakukan pembayaran simulasi, dan mencetak e-tiket.

Nama "FinalCut" diambil dari istilah dalam dunia editing film yang berarti "potongan final" — merepresentasikan momen paling krusial dalam pembuatan film.

### 1.3 Identitas Visual & Desain

Estetika visual yang digunakan adalah **indie/emo/lo-fi zine**, terinspirasi dari visual indie band dan doodle manga line-art.

| Design Token | Nilai |
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
| Font aksen tulisan tangan | Indie Flower (untuk judul informal) |

Elemen khas: obi-strip (label vertikal ala sampul buku Jepang), hard shadow, border tipis solid hitam, poster placeholder pola garis diagonal, tombol pill dengan border hitam.

---

## BAGIAN 2: BATASAN MASALAH & JUSTIFIKASI TEKNIS

> **Catatan untuk AI:** Bagian ini KRUSIAL untuk BAB 1 (Pendahuluan). Eksplorasi secara mendalam sebagai bentuk pertahanan akademis (academic defense) di hadapan dosen penguji.

### 2.1 Batasan Masalah

1. **Pembayaran Hanya Simulasi:** Aplikasi ini TIDAK mengintegrasikan payment gateway sungguhan (Midtrans, Xendit, dsb). Status pembayaran diatur secara manual oleh admin atau melalui simulasi tombol "Bayar Sekarang" yang mengubah status di database dari `pending` menjadi `paid`. Alasan: integrasi payment gateway membutuhkan proses verifikasi bisnis yang panjang dan tidak relevan untuk cakupan tugas akhir SMK.

2. **Frontend Menggunakan Blade (Bukan React/Vue):** Seluruh antarmuka pengguna dibangun dengan Laravel Blade + Tailwind CSS, bukan React, Vue, atau framework JavaScript frontend lainnya. Alasan: proyek ini harus diselesaikan dalam 9 minggu dan pengembang belum memiliki pengalaman sebelumnya dengan React/Vue. Pendekatan server-rendered dengan Blade memberikan keseimbangan terbaik antara kecepatan pengembangan dan fungsionalitas.

3. **Tidak Ada Notifikasi Real-time:** Sistem tidak mengirim push notification atau email notifikasi secara otomatis. Proses seperti perubahan status booking hanya dilihat oleh pengguna saat mereka membuka halaman riwayat transaksi.

4. **Tidak Ada Aplikasi Mobile:** FinalCut adalah aplikasi web murni yang diakses melalui peramban web (browser) di perangkat desktop maupun mobile.

### 2.2 Mengapa FinalCut Diperlukan

Saat ini, pengguna harus berpindah antar platform: membuka Letterboxd atau IMDb untuk mencari ulasan film, lalu pindah ke aplikasi bioskop terpisah (Tix.id, CGV, atau XXI) untuk membeli tiket. FinalCut menyatukan riset film (ulasan, rating, rekomendasi komunitas) dan booking tiket dalam satu alur tanpa perlu berpindah aplikasi (app-switching).

---

## BAGIAN 3: TEKNOLOGI YANG DIGUNAKAN

### 3.1 Tech Stack

| Layer | Teknologi | Versi | Keterangan |
|---|---|---|---|
| Backend | Laravel (PHP) | 12.x | Routing, Eloquent ORM, Middleware, Blade templating |
| Frontend/Template | Blade | — | Server-side rendering, bukan SPA |
| Styling | Tailwind CSS | — | Utility-first CSS framework |
| Database | MySQL | — | Database name: `db_finalcut` |
| Auth | Laravel Breeze | 2.4 | Login, register, middleware `auth` |
| PDF/Export | DomPDF (`barryvdh/laravel-dompdf`) | — | Untuk cetak laporan transaksi |
| API Token | Laravel Sanctum | 4.0 | Token-based API authentication (tersedia tapi tidak difokuskan) |
| PHP | PHP | ^8.2 | Requirement minimum Laravel 12 |
| Local Server | Laragon / XAMPP | — | Environment pengembangan lokal |

### 3.2 Package Lain yang Terinstall

| Package | Fungsi |
|---|---|
| `fakerphp/faker` | Pembuatan data dummy untuk development |
| `laravel/pail` | Log viewer real-time |
| `laravel/pint` | Code style checker |
| `pestphp/pest` | Framework testing |
| `mockery/mockery` | Mocking untuk unit testing |

### 3.3 Mengapa Laravel Dipilih

Laravel dipilih karena menyediakan fitur bawaan yang sangat relevan dengan kebutuhan proyek ini:
- **Eloquent ORM:** Mencegah SQL Injection secara otomatis karena setiap query dibuat melalui method binding, bukan string concatenation.
- **Middleware:** Sistem role-based access control yang dibangun melalui middleware `auth` dan custom middleware `role:admin`.
- **Laravel Breeze:** Scaffolding autentikasi yang sudah jadi (login, register, reset password, email verification).
- **Blade Templating:** Engine template yang powerful dengan inheritance dan component system.
- **Form Validation:** Sistem validasi server-side yang robust melalui `$request->validate()`.

---

## BAGIAN 4: PENGGUNA SISTEM (PERSONA)

### 4.1 User/Penonton

**Karakteristik:** Masyarakat umum, anak muda urban, pelajar/mahasiswa, dan pecinta film yang menonton film di bioskop secara berkala. Tidak harus cinephile berat — bisa juga orang yang sekadar ingin mengetahui rating suatu film sebelum memutuskan untuk menonton, atau ingin melihat jadwal tayang di bioskop terdekat.

**Kebutuhan:**
- Mencari informasi film (judul, sinopsis, rating komunitas, jadwal tayang)
- Menulis ulasan dan rating untuk film yang sudah ditonton
- Mengelola daftar film yang ingin ditonton (watchlist)
- Mencatat riwayat tontonan beserta tanggal (diary)
- Memesan tiket bioskop (pilih bioskop, jadwal, kursi, bayar)
- Melihat riwayat pemesanan dan mencetak e-tiket

### 4.2 Admin

**Karakteristik:** Pengelola sistem (misalnya staf pengelola bioskop atau operator platform). Bertanggung jawab atas pengelolaan data master dan pemantauan transaksi.

**Kebutuhan:**
- Mengelola data film (tambah, edit, hapus, tampil)
- Mengelola data bioskop dan studio
- Mengelola jadwal tayang (showtime)
- Memantau seluruh transaksi dari semua pengguna
- Menghasilkan laporan pendapatan dan mencetaknya dalam format PDF
- Mengelola akun pengguna (mengubah role, menonaktifkan akun)

---

## BAGIAN 5: ARSITEKTUR SISTEM & ALUR KERJA

### 5.1 Alur Autentikasi

```
[Buka Web] → [Halaman Welcome / Landing Page]
                  |
        [Login / Register]
                  |
    Register (user baru, role default: 'user')
                  |
                  v
        [Validasi Kredensial]
                  |
      +-----------+-----------+
      |                       |
  role = admin            role = user
      |                       |
      v                       |
[Dashboard Admin]        [Dashboard User]
```

**Detail Teknis:**
- Middleware `auth`: wajib login untuk mengakses halaman manapun selain Login, Register, dan Landing Page.
- Middleware `role:admin`: hanya pengguna dengan role `admin` yang boleh mengakses rute `/admin/*`.
- Jika user biasa mencoba mengakses rute admin → sistem mengembalikan HTTP 403 Forbidden.
- Registrasi otomatis mengarahkan ke halaman login, lalu login otomatis mengarahkan berdasarkan role: admin ke Dashboard Admin, user ke Dashboard User.
- Logout menghapus sesi dan CSRF token, lalu mengarahkan ke halaman utama.

### 5.2 Alur Utama User: Cari Film → Booking

```
Dashboard User
  → Browse Film (search/filter berdasarkan judul atau genre)
      → Detail Film (sinopsis, cast, trailer, rating rata-rata, ulasan komunitas)
          → Tulis Ulasan & Rating (1-5 bintang)
          → Tambah ke Watchlist / Tandai sudah ditonton (Diary)
          → Booking Tiket
                → Pilih Bioskop & Jadwal Tayang
                → Pilih Kursi (seat map interaktif)
                → Checkout / Pembayaran (pilih metode: Tunai/Kartu Kredit/Transfer Bank/E-Wallet)
                → Status booking: pending → paid / cancelled
                → Muncul di Riwayat Transaksi
                → Cetak E-Tiket (PDF)
```

### 5.3 Alur Koleksi (Fitur ala Letterboxd)

```
Detail Film → tombol "+ Watchlist" → toggle ke tabel `watchlist`
Detail Film → tombol "Tandai sudah ditonton" → isi tanggal → simpan ke tabel `watched_diary`
Koleksi Saya → tab Watchlist (grid poster) / tab Diary (daftar dengan tanggal & rating)
```

**Perbedaan Watchlist vs Diary:**
- **Watchlist:** Film yang "mau ditonton nanti". Tidak wajib ada tanggal. Satu film bisa masuk watchlist meski sudah ada di diary (mau nonton ulang).
- **Diary (watched_diary):** Film yang "sudah ditonton". Wajib ada tanggal tonton (`watched_date`). Saat user menulis ulasan pertama kali untuk suatu film, sistem otomatis membuat entri diary jika belum ada.

### 5.4 Alur Booking Lengkap (Detail Teknis)

1. **Pilih Jadwal:** User memilih film, lalu sistem menampilkan jadwal tayang yang aktif (`is_active = true`) dan masih di masa depan, dikelompokkan berdasarkan nama bioskop dan kota.

2. **Pilih Kursi:** Sistem menampilkan peta kursi untuk studio terkait. Kursi yang sudah dipesan pada jadwal yang sama (status booking selain `cancelled`) ditampilkan sebagai "terjual". Kursi dikelompokkan per baris (A, B, C, dst).

3. **Validasi Kursi:** Sistem memastikan:
   - Kursi yang dipilih valid (ada di tabel `seats` untuk studio tersebut)
   - Kursi tidak sedang dipesan oleh booking aktif lain pada showtime yang sama
   - Kursi VIP dikenakan tarif 125% dari harga dasar showtime

4. **Checkout:** Sistem menampilkan ringkasan: detail tiket (film, bioskop, studio, tanggal, jam), kursi yang dipilih (dengan tipe reguler/VIP), rincian harga per kursi, dan total.

5. **Pembayaran:** User memilih metode pembayaran (Tunai, Kartu Kredit, Transfer Bank, E-Wallet). Sistem mengubah status booking dari `pending` menjadi `paid` dan membuat record di tabel `payments` dengan status `success`.

6. **Pembatalan:** User dapat membatalkan booking selama status masih `pending`. Saat dibatalkan, kursi otomatis menjadi tersedia kembali untuk dipesan orang lain.

7. **Cetak Tiket:** User (atau admin) dapat mencetak e-tiket dalam format yang siap dicetak. Tiket berisi: kode tiket (`FC-000XXX`), detail film, detail bioskop/studio, tanggal/jam, kursi, metode bayar, total, dan status.

### 5.5 Alur Admin: Kelola Data

```
Dashboard Admin
  → Kelola Film        → Tambah/Edit/Hapus (modal form) → simpan ke `movies` + sync `movie_genre`
  → Kelola Bioskop     → Tambah/Edit/Hapus bioskop → lihat &elola Studio di dalamnya → auto-generate kursi
  → Kelola Jadwal      → Tambah/Edit/Hapus showtime → toggle status aktif/nonaktif
  → Kelola Transaksi   → Lihat semua booking dari semua user → ubah status manual
  → Laporan            → Filter berdasarkan rentang tanggal, bioskop, film → lihat grafik → export PDF
  → Kelola User        → Lihat daftar user → ubah role, nonaktifkan/aktifkan akun, reset password
```

---

## BAGIAN 6: STRUKTUR BASIS DATA

### 6.1 Ikhtisar Database

Database bernama `db_finalcut` menggunakan RDBMS MySQL. Terdiri dari 14 tabel utama beserta tabel-tabel sistem operasional framework.

### 6.2 Tabel Entitas Utama

#### 6.2.1 `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK, auto-increment | |
| name | varchar(100), NOT NULL | Nama lengkap pengguna |
| email | varchar(100), UNIQUE | Surel pengguna |
| pending_email | varchar, nullable | Email baru yang sedang diverifikasi |
| email_change_token | varchar, nullable | Token verifikasi perubahan email |
| password | varchar | Hashed password |
| role | enum('admin','user'), default 'user' | Peran pengguna |
| foto | varchar(255), nullable | Path file avatar |
| bio | text, nullable | Bio singkat pengguna |
| is_active | boolean, default true | Status akun aktif/nonaktif |
| remember_token | varchar | Token "ingat saya" |
| created_at | timestamp | Waktu pendaftaran |
| updated_at | timestamp | Waktu pembaruan terakhir |

**Relasi:**
- `hasMany(Review)` → Satu user bisa menulis banyak ulasan
- `hasMany(Booking)` → Satu user bisa melakukan banyak pemesanan
- `hasMany(Watchlist)` → Satu user bisa punya banyak film di watchlist
- `hasMany(WatchedDiary)` → Satu user bisa punya banyak entri diary

#### 6.2.2 `movies`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| title | varchar(150), NOT NULL | Judul film |
| sinopsis | text, nullable | Sinopsis/deskripsi film |
| durasi | int unsigned, nullable | Durasi dalam menit |
| rating_umur | varchar(10), default 'SU' | Rating usia (SU, 13+, 17+, 21+) |
| poster | varchar(255), nullable | URL/path gambar poster |
| trailer_url | varchar(255), nullable | URL trailer (YouTube embed) |
| release_date | date, nullable | Tanggal rilis |
| director | varchar(100), nullable | Nama sutradara |
| created_at | timestamp | |
| updated_at | timestamp | |

**Relasi:**
- `belongsToMany(Genre)` melalui pivot `movie_genre` → Satu film punya banyak genre
- `hasMany(Review)` → Satu film punya banyak ulasan
- `hasMany(Showtime)` → Satu film punya banyak jadwal tayang
- `hasMany(Watchlist)` → Satu film bisa ada di watchlist banyak user
- `hasMany(WatchedDiary)` → Satu film bisa ada di diary banyak user

#### 6.2.3 `genres`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| name | varchar(50), UNIQUE | Nama genre (Action, Drama, Horror, Comedy, Sci-Fi) |

Tanpa timestamps. Contoh data: Action, Drama, Horror, Comedy, Sci-Fi.

#### 6.2.4 `movie_genre` (Tabel Pivot Many-to-Many)

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK | |
| movie_id | int unsigned, FK → movies(id) ON DELETE CASCADE | |
| genre_id | int unsigned, FK → genres(id) ON DELETE CASCADE | |

**Unique constraint:** `(movie_id, genre_id)` — satu film tidak boleh punya genre yang sama dua kali.

#### 6.2.5 `reviews`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| user_id | int unsigned, FK → users(id) ON DELETE CASCADE | |
| movie_id | int unsigned, FK → movies(id) ON DELETE CASCADE | |
| rating | tinyint unsigned, CHECK (1-5) | Rating bintang 1-5 |
| review_text | text, nullable | Teks ulasan (opsional) |
| created_at | timestamp | Waktu ulasan ditulis |

**Catatan:** Tabel ini TIDAK memiliki kolom `updated_at` — ulasan yang sudah ditulis tidak bisa diubah (hanya bisa dihapus dan ditulis ulang). Rating dijamin antara 1-5 melalui CHECK constraint di database.

**Relasi:**
- `belongsTo(User)` → Ulasan ditulis oleh satu user
- `belongsTo(Movie)` → Ulasan ditulis untuk satu film

#### 6.2.6 `watchlist`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK | |
| user_id | int unsigned, FK → users(id) ON DELETE CASCADE | |
| movie_id | int unsigned, FK → movies(id) ON DELETE CASCADE | |
| created_at | timestamp | Waktu ditambahkan ke watchlist |

**Unique constraint:** `(user_id, movie_id)` — satu user tidak bisa menambahkan film yang sama ke watchlist dua kali.

**Relasi:** `belongsTo(User)`, `belongsTo(Movie)`

#### 6.2.7 `watched_diary`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK | |
| user_id | int unsigned, FK → users(id) ON DELETE CASCADE | |
| movie_id | int unsigned, FK → movies(id) ON DELETE CASCADE | |
| watched_date | date | Tanggal film ditonton |
| created_at | timestamp | Waktu entri dibuat |

**Relasi:** `belongsTo(User)`, `belongsTo(Movie)`

#### 6.2.8 `cinemas`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| name | varchar(100) | Nama bioskop |
| address | varchar(255), nullable | Alamat |
| city | varchar(100), nullable | Kota |
| latitude | decimal(10,8), nullable | Koordinat lintang |
| longitude | decimal(11,8), nullable | Koordinat bujur |

Tanpa timestamps. Tanpa foreign key.

**Relasi:** `hasMany(Studio)` → Satu bioskop punya banyak studio.

#### 6.2.9 `studios`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| cinema_id | int unsigned, FK → cinemas(id) ON DELETE CASCADE | |
| name | varchar(50) | Nama studio (Studio 1, Studio 2, dst.) |
| capacity | int unsigned, default 0 | Kapasitas kursi |

Tanpa timestamps.

**Relasi:**
- `belongsTo(Cinema)` → Studio milik satu bioskop
- `hasMany(Seat)` → Satu studio punya banyak kursi

#### 6.2.10 `showtimes`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| movie_id | int unsigned, FK → movies(id) ON DELETE CASCADE | |
| studio_id | int unsigned, FK → studios(id) ON DELETE CASCADE | |
| show_date | date | Tanggal tayang |
| show_time | time | Jam tayang |
| price | decimal(10,2) | Harga tiket per kursi |
| is_active | boolean, default true | Status aktif/nonaktif jadwal |

Tanpa timestamps.

**Relasi:**
- `belongsTo(Movie)` → Jadwal untuk satu film
- `belongsTo(Studio)` → Jadwal di satu studio
- `hasMany(Booking)` → Satu jadwal punya banyak pemesanan

#### 6.2.11 `seats`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| studio_id | int unsigned, FK → studios(id) ON DELETE CASCADE | |
| seat_number | varchar(10) | Nomor kursi (A1, A2, B1, B2, dst.) |
| seat_type | enum('reguler','vip'), default 'reguler' | Tipe kursi |

**Unique constraint:** `(studio_id, seat_number)` — nomor kursi unik per studio.

**Relasi:**
- `belongsTo(Studio)` → Kursi milik satu studio
- `belongsToMany(Booking)` melalui pivot `booking_seats` → Satu kursi bisa ada di banyak booking (untuk showtime berbeda)

#### 6.2.12 `bookings`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| user_id | int unsigned, FK → users(id) ON DELETE CASCADE | |
| showtime_id | int unsigned, FK → showtimes(id) ON DELETE CASCADE | |
| total_price | decimal(10,2) | Total harga = harga per kursi × jumlah kursi (dengan surcharge VIP 125%) |
| status | enum('pending','paid','cancelled'), default 'pending' | Status pemesanan |
| booking_date | timestamp | Waktu pemesanan dibuat |

**Relasi:**
- `belongsTo(User)` → Booking milik satu user
- `belongsTo(Showtime)` → Booking untuk satu jadwal tayang
- `belongsToMany(Seat)` melalui pivot `booking_seats` → Satu booking punya banyak kursi
- `hasOne(Payment)` → Satu booking punya satu record pembayaran

#### 6.2.13 `payments`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK, auto-increment | |
| booking_id | int unsigned, FK → bookings(id) ON DELETE CASCADE | |
| amount | decimal(10,2) | Jumlah pembayaran |
| method | varchar(50), nullable | Metode: Tunai, Kartu Kredit, Transfer Bank, E-Wallet |
| status | enum('pending','success','failed'), default 'pending' | Status pembayaran |
| payment_date | timestamp, nullable | Waktu pembayaran dilakukan |

**Relasi:** `belongsTo(Booking)`

#### 6.2.14 `booking_seats` (Tabel Pivot)

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int, PK | |
| booking_id | int unsigned, FK → bookings(id) ON DELETE CASCADE | |
| seat_id | int unsigned, FK → seats(id) ON DELETE CASCADE | |

**Unique constraint:** `(booking_id, seat_id)` — satu booking tidak boleh memuat kursi yang sama dua kali.

### 6.3 Diagram Relasi (ERD Narrative)

```
users ──1:N──> reviews <──N:1── movies ──M:N──> genres (via movie_genre)
users ──1:N──> bookings ──1:1──> payments
users ──1:N──> watchlist ──N:1──> movies
users ──1:N──> watched_diary ──N:1──> movies

movies ──1:N──> showtimes ──N:1──> studios ──N:1──> cinemas

bookings ──M:N──> seats (via booking_seats)
showtimes ──1:N──> bookings

studios ──1:N──> seats
cinemas ──1:N──> studios
```

---

## BAGIAN 7: MANAJEMEN AKUN & PROFIL

### 7.1 Fitur Profil Pengguna

**Halaman Profil (profile/show):**
- Menampilkan avatar pengguna (foto atau inisial nama jika belum ada foto)
- Badge "Admin" jika role adalah admin
- Bio pengguna
- Tanggal bergabung (created_at)
- Statistik: jumlah ulasan, jumlah diary, jumlah watchlist
- Daftar poster diary terbaru (10 item)
- Daftar poster watchlist terbaru (10 item)
- Daftar ulasan terbaru (5 item)
- Link ke halaman pengaturan (jika itu profil sendiri)

### 7.2 Pengaturan Profil (profile/settings)

Bagian pengaturan terdiri dari beberapa seksi:

1. **Ubah Foto Profil:**
   - Upload foto avatar (format: jpg, jpeg, png, webp; maks 2MB)
   - Preview foto sebelum diunggah
   - Hapus foto profil (foto lama otomatis dihapus dari server)
   - Foto disimpan di `public/uploads/avatars/` dengan nama `{timestamp}_{user_id}.{ext}`

2. **Ubah Nama & Bio:**
   - Nama: wajib diisi, maks 100 karakter
   - Bio: opsional, maks 300 karakter

3. **Ubah Email:**
   - Memasukkan email baru dan password saat ini untuk konfirmasi
   - Sistem membuat token verifikasi dan menyimpan email baru di `pending_email`
   - Pengguna mengklik tautan verifikasi untuk mengonfirmasi perubahan
   - Email lama tetap berlaku sampai email baru diverifikasi

4. **Ubah Password:**
   - Wajib memasukkan password saat ini
   - Password baru minimal 8 karakter
   - Konfirmasi password baru

5. **Hapus Akun:**
   - Wajib memasukkan password untuk konfirmasi
   - Foto profil dihapus dari server
   - Sesi dihapus dan pengguna di-logout
   - Semua data terkait (ulasan, booking, watchlist, diary) ikut terhapus (karena ON DELETE CASCADE)

### 7.3 Profil Pengguna Lain

Setiap pengguna memiliki profil publik yang bisa dilihat oleh pengguna lain (`/profile/{user}`). Halaman ini menampilkan informasi yang sama dengan profil sendiri, tetapi tanpa link ke pengaturan.

---

## BAGIAN 8: FITUR UTAMA APLIKASI (CORE FEATURES)

### 8.1 Halaman Welcome / Landing Page

Halaman pembuka yang bisa diakses tanpa login. Berisi:
- Header navigasi (tautan Login/Register untuk tamu, atau Dashboard untuk yang sudah login)
- Hero section dengan visual CSS cinema ticket art (floating cards, barcode)
- Penjelasan "Dua Pilar Utama": Cinephile Pillar dan Booking Pillar
- "Cara Kerja" 3 langkah: Cari Film → Pilih Jadwal → Dapat Tiket
- Ulasan terbaru dari database
- Strip statistik: jumlah film, anggota, ulasan, dan tiket terjual
- Call-to-action untuk mendaftar

### 8.2 Modul Film (User)

#### 8.2.1 Daftar Film (movies/index)
- Grid responsif 2/3/4 kolom (tergantung ukuran layar)
- Pencarian berdasarkan judul atau sutradara
- Filter berdasarkan genre
- Setiap kartu film menampilkan: poster, badge rating usia, badge "Sedang Tayang" (jika punya showtime aktif), tag genre
- Paginasi 12 film per halaman

#### 8.2.2 Sedang Tayang (movies/now-showing)
- Sama seperti daftar film, tetapi hanya menampilkan film yang memiliki showtime aktif
- Diurutkan berdasarkan jumlah showtime aktif (terbanyak di atas)
- Tautan mengarah ke alur booking langsung

#### 8.2.3 Detail Film (movies/show)
- Tampilan dua kolom:
  - **Kiri:** Poster, judul, badge rating usia, durasi, tahun rilis, genre, sutradara, sinopsis, tombol aksi (Pesan Tiket, Tambah ke Watchlist, Tandai Sudah Ditonton), embed trailer YouTube
  - **Kanan:** Ringkasan rating komunitas (rata-rata dari semua ulasan, dihitung on-the-fly), formulir ulasan (rating 1-5 + teks opsional, bisa edit/hapus ulasan sendiri), daftar ulasan komunitas dengan avatar penulis

### 8.3 Modul Booking (User)

#### 8.3.1 Pilih Bioskop & Jadwal (bookings/select-showtime)
- Menampilkan informasi film di bagian atas
- Jadwal tayang dikelompokkan berdasarkan nama bioskop → tanggal → jam
- Setiap jadwal ditampilkan sebagai tombol pill dengan jam dan harga
- Hanya menampilkan jadwal yang masih aktif dan di masa depan

#### 8.3.2 Pilih Kursi (bookings/select-seats)
- Peta kursi interaktif berbasis JavaScript vanilla (tanpa framework)
- Kursi ditampilkan dalam grid baris per baris (A1, A2, A3... B1, B2, B3...)
- Indikator layar di bagian atas
- Kursi tersedia: bisa diklik
- Kursi terjual (sudah dipesan pada showtime yang sama): ditandai merah, tidak bisa diklik
- Kursi dipilih: ditandai biru, bisa diklik lagi untuk membatalkan pemilihan
- Legend: Reguler, VIP (harganya 125% dari harga dasar), Dipilih, Terjual
- Sidebar: ringkasan pesanan, daftar kursi yang dipilih, rincian harga per kursi (reguler vs VIP), total harga
- Form submission ke backend

#### 8.3.3 Checkout (bookings/checkout)
- Detail tiket: poster, judul film, bioskop, studio, tanggal, jam
- Daftar kursi yang dipilih (dengan badge tipe reguler/VIP)
- Ringkasan pembayaran: harga per tipe kursi × jumlah, total
- Pilihan metode pembayaran: Tunai, Kartu Kredit, Transfer Bank, E-Wallet (simulasi)
- Modal konfirmasi sebelum membayar
- Tombol batal

#### 8.3.4 Detail Booking (bookings/show)
- Tampilan tergantung status:
  - **Pending:** tombol "Bayar Sekarang" dan "Batalkan"
  - **Paid:** informasi pembayaran berhasil, tombol "Cetak Tiket"
  - **Cancelled:** informasi pembatalan
- Detail lengkap: kode tiket (`FC-000XXX`), film, bioskop, studio, tanggal, jam, kursi, metode bayar, total, status

#### 8.3.5 Riwayat Transaksi (bookings/history)
- Daftar semua booking user, diurutkan dari yang terbaru
- Paginasi 10 item per halaman
- Setiap kartu: poster film, judul, bioskop, badge status, tanggal, kursi, harga, tombol aksi (Bayar/Batalkan untuk pending, Detail untuk semua)

#### 8.3.6 Cetak Tiket (bookings/print)
- Halaman cetak mandiri (tanpa layout navigasi)
- Tiket berisi: kode tiket, detail film, detail bioskop/studio, tanggal/jam, kursi dengan tipe, metode bayar, status, total
- Desain tiket bergaya karcis bioskop dengan barcode-style code
- Tombol cetak (tersembunyi saat mode print browser)

### 8.4 Modul Koleksi (User)

#### 8.4.1 Koleksi Saya (collection/index)
- Tab-based interface: tab "Watchlist" dan tab "Diary"
- **Tab Watchlist:** Grid poster film dengan tombol hapus overlay. Klik poster → menuju detail film.
- **Tab Diary:** Daftar list dengan tanggal tonton, poster, judul film, tahun, rating bintang, indikator apakah sudah ada ulasan. Sortir berdasarkan: terbaru, terlama, rating tertinggi, rating terendah, A-Z. Aksi: edit tanggal, hapus dari diary.

### 8.5 Modul Ulasan (User)

#### 8.5.1 Menulis Ulasan
- Rating wajib 1-5 bintang
- Teks ulasan opsional (maks 1000 karakter)
- Satu user hanya boleh menulis satu ulasan per film (jika sudah ada, form berubah menjadi "Edit Ulasan")
- Saat menulis ulasan pertama kali untuk suatu film, sistem otomatically membuat entri di `watched_diary` jika belum ada (dengan tanggal hari ini)
- Rata-rata rating film dihitung on-the-fly dari seluruh ulasan

#### 8.5.2 Menghapus Ulasan
- Hanya pemilik ulasan yang bisa menghapusnya
- Modal konfirmasi sebelum menghapus

### 8.6 Modul Dashboard Admin

**Halaman Dashboard Admin (admin/dashboard):**
- Banner sapa dengan nama admin
- 4 kartu statistik: Total User, Total Film, Total Booking, Total Pendapatan (dalam format Rupiah)
- 6 tombol aksi cepat: Kelola Film, Kelola Bioskop, Jadwal Tayang, Transaksi, Laporan, Kelola User
- Tabel Top 5 Film berdasarkan jumlah booking beserta pendapatan masing-masing
- Ringkasan status booking: paid, pending, cancelled, jadwal aktif
- Tabel booking terbaru (user, film, bioskop, status, total, tanggal)

### 8.7 Modul Kelola Film (Admin)

**Halaman admin/movies:**
- Pencarian berdasarkan judul atau sutradara
- Filter berdasarkan genre
- Tabel daftar film dengan paginasi 10
- Tombol "Tambah Film" → modal form
- Setiap baris: edit (modal), hapus (konfirmasi)
- Halaman detail: informasi film, ulasan, jadwal tayang, rata-rata rating

**Form Tambah/Edit Film:**
- Judul (wajib, maks 150)
- Sinopsis (opsional)
- Durasi dalam menit (opsional, minimal 1)
- Rating Usia (opsional, maks 10 karakter, default SU)
- URL Poster (opsional, maks 255)
- URL Trailer (opsional, maks 255, format YouTube embed URL)
- Tanggal Rilis (opsional)
- Sutradara (opsional, maks 100)
- Genre (wajib, multi-select, minimal 1 genre harus dipilih)

### 8.8 Modul Kelola Bioskop & Studio (Admin)

**Halaman admin/cinemas:**
- Pencarian berdasarkan nama atau kota
- Tabel daftar bioskop dengan jumlah studio
- Form tambah/edit bioskop: Nama (wajib), Kota (wajib), Alamat (wajib)
- Link ke halaman studio untuk setiap bioskop

**Halaman admin/cinemas/{id}/studios:**
- Daftar studio dalam suatu bioskop beserta jumlah kursi
- Form tambah studio: Nama (wajib, maks 50), Baris (wajib, 1-26), Kolom (wajib, 1-50)
- Sistem otomatis membuat kursi saat studio baru ditambahkan:
  - Nomor kursi: A1, A2, A3... B1, B2, B3... (baris pakai huruf A-Z, kolom pakai angka)
  - Baris pertama (A) = VIP, baris lainnya = Reguler
  - Total kursi = baris × kolom
- Tombol hapus studio

### 8.9 Modul Kelola Jadwal Tayang (Admin)

**Halaman admin/showtimes:**
- Filter: tanggal, bioskop, status (aktif/nonaktif/semua)
- Tabel daftar jadwal dengan paginasi 15
- Form tambah/edit: Film (wajib), Studio (wajib), Tanggal (wajib), Jam (format HH:ii), Harga (wajib, minimal 0)
- Toggle status aktif/nonaktif untuk setiap jadwal
- Saat dibuat, jadwal otomatis aktif

### 8.10 Modul Kelola Transaksi (Admin)

**Halaman admin/transactions:**
- Filter: status (pending/paid/cancelled/semua), rentang tanggal, bioskop, pencarian (nama/email user atau judul film)
- Tabel semua booking dari semua pengguna dengan paginasi 15
- Detail: user, film, bioskop, studio, tanggal/jam, kursi, total, status, tanggal booking
- Ubah status: pending → paid / cancelled
  - Jika diubah ke paid (sebelumnya bukan paid): buat/update record payment
  - Jika diubah ke cancelled (sebelumnya bukan cancelled): tandai payment yang ada sebagai `failed`

### 8.11 Modul Laporan & Rekapitulasi (Admin)

**Halaman admin/reports:**
- Filter: rentang tanggal (default bulan berjalan), bioskop, film
- Statistik: total pendapatan, total booking, total tiket terjual
- Grafik/jumlah pendapatan per film (top 10)
- Pendapatan per bioskop
- Pendapatan per tanggal
- **Export PDF:** Generate laporan dalam format PDF landscape A4 menggunakan DomPDF
  - Filename: `laporan-finalcut-{tanggal_awal}-sd-{tanggal_akhir}.pdf`
  - Berisi ringkasan data, tabel pendapatan per film, dan total

### 8.12 Modul Kelola User (Admin)

**Halaman admin/users:**
- Filter: pencarian (nama/email), role (admin/user/semua), status (active/inactive/semua)
- Tabel daftar user dengan paginasi 15
- Form edit: nama, email (unique, kecuali milik sendiri), role, status aktif
- Toggle aktif/nonaktif: admin tidak bisa menonaktifkan akun sendiri (self-protection)
- Reset password: mengatur ulang password user ke string default `'password'`

---

## BAGIAN 9: IMPLEMENTASI KEAMANAN

> **Catatan untuk AI:** Bagian ini sangat penting untuk BAB 2 atau BAB 5 sebagai justifikasi teknis keamanan sistem. Eksplorasi secara mendalam.

### 9.1 Pencegahan SQL Injection

Seluruh query database dijalankan melalui **Eloquent ORM** dan **Query Builder** Laravel. Metode ini menggunakan prepared statements dan parameter binding secara otomatis, sehingga input pengguna tidak pernah dihubungkan langsung ke string query SQL. Contoh:
- `$movies = Movie::where('title', 'LIKE', "%$search%")->get();` — parameter `$search` di-binding secara terpisah.
- Relasi Many-to-Many menggunakan `belongsToMany()` dengan pivot table, bukan raw JOIN.
- **Tidak ada satu pun raw SQL query (`DB::raw()`) yang menerima input langsung dari pengguna di seluruh kode aplikasi.**

### 9.2 Proteksi Middleware

| Rute | Middleware | Keterangan |
|---|---|---|
| `/login`, `/register` | — (guest only) | Hanya bisa diakses yang belum login |
| `/dashboard`, `/movies/*`, `/booking/*`, `/collection`, `/profile` | `auth` | Hanya user & admin yang sudah login |
| `/admin/*` | `auth`, `role:admin` | Hanya admin |
| Edit/hapus ulasan | `auth` + cek `user_id` di controller | Hanya pemilik ulasan sendiri |
| Booking (checkout, bayar, cetak) | `auth` + cek `user_id` di controller | Hanya pemilik booking sendiri (admin dikecualikan untuk cetak) |

### 9.3 Validasi Input Server-Side

Semua input dari pengguna divalidasi di sisi server menggunakan `$request->validate()` atau Form Request. Contoh:
- Form ulasan: rating wajib 1-5, teks maks 1000 karakter
- Form film: judul wajib maks 150 karakter, genre wajib minimal 1
- Form booking: showtime_id wajib ada di database, seat_ids wajib berupa string
- Form pembayaran: method wajib salah satu dari 4 opsi yang tersedia

### 9.4 Autentikasi & Sesi

- Menggunakan Laravel Breeze untuk scaffolding autentikasi
- Password di-hash menggunakan bcrypt (melalui `$casts` di model User)
- Sesi dikelola oleh Laravel secara otomatis
- CSRF token diverifikasi pada setiap form submission
- Session regeneration setelah login

---

## BAGIAN 10: INSTALASI & PENJALANAN

### 10.1 Kebutuhan Sistem

**Perangkat Keras (Minimum):**
- RAM: 2 GB
- Penyimpanan: 500 MB kosong
- Koneksi internet stabil (untuk instalasi awal)

**Perangkat Lunak:**
- Sistem Operasi: Windows 10/11, macOS, atau Linux
- Web Server: Laragon atau XAMPP (Apache + MySQL)
- PHP: 8.2 atau lebih baru
- Composer: Versi terbaru
- Node.js & npm: Untuk build asset Tailwind CSS
- Visual Studio Code: Sebagai editor kode
- Web Browser: Chrome/Firefox/Edge versi terbaru

### 10.2 Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/[username]/FinalCut_code.git
cd FinalCut_code

# 2. Install dependency PHP
composer install

# 3. Copy file environment
copy .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=db_finalcut
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi
php artisan migrate

# 7. Jalankan seeder (untuk data contoh)
php artisan db:seed

# 8. Install dependency JavaScript & build
npm install
npm run build

# 9. Jalankan server lokal
php artisan serve
```

Server lokal berjalan di `http://127.0.0.1:8000`.

### 10.3 Data Seeder

Setelah menjalankan `php artisan db:seed`, sistem akan membuat:

| Data | Keterangan |
|---|---|
| Admin FinalCut | Email: admin@finalcut.test, Password: password, Role: admin |
| User Percobaan | Email: user@finalcut.test, Password: password, Role: user |
| 5 Genre | Action, Drama, Horror, Comedy, Sci-Fi |
| 1 Bioskop | FinalCut Cineplex - Paskal, Jl. Pasirkaliki No. 25, Bandung |
| 1 Studio | Studio 1 (kapasitas 40 kursi) |
| 4 Kursi | A1 (reguler), A2 (reguler), B1 (vip), B2 (vip) |

---

## BAGIAN 11: TROUBLESHOOTING

> **Catatan untuk AI:** Bab ini membahas kendala umum yang mungkin dihadapi pengguna atau pengembang beserta solusinya.

### 11.1 Error Koneksi Database

**Kode Error:** `SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it`

**Penyebab:** Layanan MySQL belum aktif atau belum berjalan.

**Solusi:**
1. Pastikan Laragon atau XAMPP sudah terbuka
2. Pastikan layanan MySQL dalam status "Running" (centang di panel kontrol)
3. Periksa konfigurasi `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`
4. Pastikan nama database `db_finalcut` sudah dibuat di MySQL

### 11.2 Error 403 Forbidden

**Kode Error:** `403 Forbidden`

**Penyebab:** Pengguna mencoba mengakses halaman yang tidak memiliki hak akses. Misalnya, user biasa mencoba membuka `/admin/dashboard`.

**Solusi:**
1. Pastikan login dengan akun yang memiliki role sesuai
2. Jika role sudah benar, periksa kolom `role` di tabel `users` di database
3. Bersihkan cache: `php artisan cache:clear`

### 11.3 Error 419 Page Expired

**Kode Error:** `419 Page Expired / CSRF Token Mismatch`

**Penyebab:** Token CSRF pada form sudah kedaluwarsa (biasanya karena terlalu lama membuka halaman tanpa submit).

**Solusi:**
1. Muat ulang (refresh) halaman
2. Isi ulang form dan submit kembali

### 11.4 Error 500 Internal Server Error

**Kode Error:** `500 Internal Server Error`

**Penyebab:** Terjadi kesalahan pada kode backend atau konfigurasi server.

**Solusi:**
1. Periksa file log error di `storage/logs/laravel.log`
2. Jalankan: `php artisan config:clear && php artisan cache:clear`
3. Pastikan file `.env` terkonfigurasi dengan benar
4. Pastikan `APP_KEY` sudah di-generate (`php artisan key:generate`)

### 11.5 Menu/Navigation Tidak Muncul dengan Benar

**Penyebab:** File layout Blade belum di-build atau asset CSS/JS belum di-compile.

**Solusi:**
1. Jalankan `npm run build` untuk mengcompile asset Tailwind CSS
2. Jalankan `php artisan view:clear` untuk membersihkan cache view
3. Muat ulang browser dengan `Ctrl+Shift+R` (hard refresh)

### 11.6 File Upload Foto Profil Gagal

**Penyebab:** Ukuran file melebihi batas atau format tidak sesuai.

**Solusi:**
1. Pastikan ukuran file maksimal 2 MB
2. Pastikan format file: JPG, JPEG, PNG, atau WebP
3. Pastikan direktori `public/uploads/avatars/` memiliki izin tulis

---

## BAGIAN 12: HALAMAN ADMINISTRATIF LAPORAN

> **Catatan untuk AI:** Bagian ini memandu Anda untuk membuat halaman-halaman administratif di awal laporan sebelum BAB I. Gunakan placeholder `[NAMA]`, `[NIS]`, dll.

### 12.1 Halaman Sampul

```
LAPORAN AKHIR PEMBUATAN PROJEK WEBSITE
FINALCUT — PLATFORM ULASAN FILM & PEMESANAN TIKET BIOSKOP TERINTEGRASI

Disusun untuk memenuhi salah satu persyaratan dalam menyelesaikan tugas
mata Pelajaran Pemrograman Web di [NAMA SEKOLAH]
kompetensi keahlian Rekayasa Perangkat Lunak

Disusun oleh:
[NAMA LENGKAP SISWA] ([NIS])

PEMERINTAH DAERAH PROVINSI [PROVINSI]
DINAS PENDIDIKAN
SEKOLAH MENENGAH KEJURUAN NEGERI [NOMOR SEKOLAH]
[NAMA JALAN]
Tahun Ajaran [TAHUN AJARAN]
```

### 12.2 Lembar Pengesahan

Sertakan informasi:
- Judul laporan
- Nama penulis dan NIS
- Tanda tangan dan nama Pembimbing
- Tanda tangan dan nama Kepala Program Keahlian RPL

Gunakan placeholder:
- `[NAMA PEMBIMBING]`, `[NIP PEMBIMBING]`
- `[NAMA KEPALA PROGRAM KEAHLIAN]`, `[NIP KEPALA PROGRAM]`

### 12.3 Lembar Originalitas Karya

Sertakan pernyataan orisinalitas karya dengan:
- Nama lengkap dan NIS
- Pernyataan bahwa karya ini orisinal dan bukan hasil plagiarisme
- Tanggal dan tanda tangan

### 12.4 Identitas Siswa

Sertakan:
- Nama Siswa, NIS/NISN
- Tempat/Tanggal Lahir
- Alamat
- Jenis Kelamin, Agama
- No. Telepon/HP, Email
- Nama Sekolah, Alamat, No. Telepon
- Nama Orang Tua/Wali, Alamat, No. Telepon

Gunakan placeholder: `[NAMA SISWA]`, `[NIS]`, `[NISN]`, `[TEMPAT LAHIR]`, `[TANGGAL LAHIR]`, `[ALAMAT SISWA]`, `[JENIS KELAMIN]`, `[AGAMA]`, `[NO HP]`, `[EMAIL]`, `[NAMA ORANG TUA]`, `[ALAMAT ORANG TUA]`, `[NO HP ORANG TUA]`

### 12.5 Kata Pengantar

Tulis kata pengantar formal yang mencakup:
- Ucapan puji syukur
- Tujuan laporan disusun
- Ucapan terima kasih kepada: Allah SWT, orang tua, Kepala Sekolah, Kepala Program Keahlian, Pembimbing, guru-guru, dan pihak lain
- Harapan agar laporan bermanfaat
- Permohonan maaf atas kekurangan

### 12.6 Selayar Pandang

Tulis ringkasan eksekutif proyek FinalCut (±1 paragraf), menjelaskan:
- Apa itu FinalCut
- Masalah apa yang diselesaikan
- Fitur utama yang ditawarkan
- Siapa target penggunanya

### 12.7 Daftar Isi

Buat daftar isi lengkap dari Halaman Sampul hingga Lampiran dengan nomor halaman.

### 12.8 Daftar Gambar

Buat daftar gambar yang akan disertakan dalam laporan, contoh:
- Gambar 3.1: Tampilan Skema Alur Kerja
- Gambar 3.2: Tampilan Struktur Database
- Gambar 4.1: Tampilan Landing Page
- Gambar 4.2: Tampilan Halaman Login
- Gambar 4.3: Tampilan Dashboard User
- Gambar 4.4: Tampilan Dashboard Admin
- Gambar 5.1: Tampilan Daftar Film
- Gambar 5.2: Tampilan Detail Film
- Gambar 5.3: Tampilan Peta Kursi
- Gambar 5.4: Tampilan Checkout
- Gambar 5.5: Tampilan Riwayat Transaksi
- Gambar 5.6: Tampilan Cetak Tiket
- Gambar 5.7: Tampilan Koleksi Saya (Watchlist & Diary)
- Gambar 5.8: Tampilan Kelola Film (Admin)
- Gambar 5.9: Tampilan Laporan & Export PDF (Admin)

Gunakan placeholder: `[GAMBAR: Screenshot Landing Page]`, `[GAMBAR: Screenshot Login]`, dst.

### 12.9 Daftar Pustaka

Sertakan referensi akademis dan teknis:

**Referensi Teknologi:**
- Laravel Documentation. (2026). Laravel: The PHP Framework for Web Artisans. https://laravel.com/docs
- Tailwind Labs. (2026). Tailwind CSS: A utility-first CSS framework for rapid UI development. https://tailwindcss.com/docs
- PHP: Hypertext Preprocessor. (2026). PHP Manual. https://www.php.net/manual/
- MySQL. (2026). MySQL 8.0 Reference Manual. https://dev.mysql.com/doc/refman/8.0/en/
- Barraquand, B. (2026). DomPDF — PHP library to render PDF from HTML. https://github.com/barryvdh/laravel-dompdf
- Laravel Breeze. (2026). Simple Laravel Authentication Scaffolding. https://laravel.com/docs/12.x/starter-kits

**Referensi Akademis:**
- Connolly, T. M., & Begg, C. E. (2015). Database Systems: A Practical Approach to Design, Implementation, and Management (6th ed.). Boston: Pearson Education.
- Duckett, J. (2014). JavaScript and JQuery: Interactive Front-End Web Development. Indianapolis: John Wiley & Sons, Inc.
- Fowler, M. (2018). Refactoring: Improving the Design of Existing Code (2nd ed.). Boston: Addison-Wesley Professional.
- Nixon, R. (2021). Learning PHP, MySQL & JavaScript: With Step-by-Step Guide to Responsive Web Design. Sebastopol: O'Reilly Media.
- Pressman, R. S., & Maxim, B. R. (2019). Software Engineering: A Practitioner's Approach (9th ed.). New York: McGraw-Hill Education.

### 12.10 Lampiran

Sertakan:
- **Lampiran 1:** Aset Identitas Visual & Logo FinalCut (jika ada logo)
- **Lampiran 2:** Desain Antarmuka, Skema Basis Data, dan Alur Kerja
  - Gambar Lampiran 2.1: Skema Database (ERD)
  - Gambar Lampiran 2.2: Tampilan Halaman Website Utama
  - Gambar Lampiran 2.3: Skema Alur Kerja Sistem

---

## BAGIAN 13: STRUKTUR FOLDER PROYEK

```
FinalCut_code/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    (8 controller autentikasi)
│   │   │   ├── Admin/                   (6 controller admin)
│   │   │   ├── BookingController.php
│   │   │   ├── CollectionController.php
│   │   │   ├── EmailChangeController.php
│   │   │   ├── MovieController.php
│   │   │   ├── ProfileController.php
│   │   │   └── ReviewController.php
│   │   ├── Middleware/
│   │   │   └── EnsureUserHasRole.php
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Models/                          (13 model + 1 pivot)
│   ├── Providers/
│   └── View/Components/
├── database/
│   ├── migrations/                      (21 migration files)
│   └── seeders/DatabaseSeeder.php
├── public/
│   ├── uploads/avatars/                 (foto profil pengguna)
│   └── js/welcome.js
├── resources/views/
│   ├── admin/                           (7 halaman admin)
│   ├── auth/                            (6 halaman autentikasi)
│   ├── bookings/                        (6 halaman booking)
│   ├── collection/                      (1 halaman koleksi)
│   ├── components/                      (13 komponen Blade)
│   ├── layouts/                         (3 layout: app, guest, navigation)
│   ├── movies/                          (3 halaman film)
│   └── profile/                         (4 halaman profil + partials)
├── routes/web.php
├── Context_FinalCut.md                  (dokumen ini)
└── composer.json
```

Total halaman Blade: 53 file.

---

**— AKHIR DOKUMEN KONTEKS —**
