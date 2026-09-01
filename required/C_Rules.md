# FinalCut — Coding Rules & Conventions
**Tingkat kepentingan: C (pelengkap) — panduan teknis harian, supaya kode konsisten dari awal sampai akhir**

## 1. Aturan Wajib (Non-negotiable)
1. **Jangan pakai React/Vue/framework JS lain.** Frontend murni Blade + Tailwind CSS. Ini sudah diputuskan sadar karena keterbatasan waktu 9 minggu — lihat `A_ProjectSpec.md`.
2. **Semua query database lewat Eloquent ORM**, bukan `DB::raw()` dengan string concatenation dari input user — poin penilaian eksplisit ("Cegah SQL Injection").
3. **Setiap route baru wajib punya middleware yang sesuai** — lihat tabel middleware di `B_Flow.md`.
4. **Validasi input selalu di server-side** (Laravel Form Request atau `$request->validate()`), tidak cukup validasi JS saja.
5. Ikuti design token yang sudah ditetapkan di `B_Concept.md` — jangan improvisasi warna/font baru tanpa persetujuan user.

## 2. Struktur Folder (standar Laravel, tidak perlu diubah)
```
app/Http/Controllers/   -> 1 controller per entitas (MovieController, BookingController, dst.)
app/Models/              -> 1 model per tabel
resources/views/         -> Blade views, dikelompokkan per fitur (movies/, bookings/, admin/, dst.)
public/css/              -> file CSS custom (kalau tidak pakai Vite build)
routes/web.php           -> semua route web
database/migrations/     -> migration untuk 14 tabel
```

## 3. Konvensi Penamaan
- Tabel: snake_case, jamak (`movies`, `booking_seats`)
- Model: PascalCase, tunggal (`Movie`, `BookingSeat`)
- Route name: `resource.action` (`movies.index`, `movies.show`, `bookings.history`)
- Blade view file: kebab-case sesuai nama fitur (`dashboard.blade.php`, `movie-detail.blade.php`)
- Variabel Blade dari controller: camelCase (`$watchlistCount`, `$nowShowing`)

## 4. Pola UI Admin (List + Modal)
Halaman "Kelola ..." di admin **tidak boleh** dipecah jadi halaman terpisah untuk Tambah/Edit. Gunakan pola: tabel list di halaman utama + modal untuk form tambah/edit, supaya jumlah halaman tetap 17 (lihat `B_Concept.md` untuk alasan lengkap).

## 5. Checklist Sebelum Anggap Fitur "Selesai"
- [ ] Sudah dites tombol/form-nya, tidak ada error di console/log
- [ ] Middleware role sudah terpasang kalau halaman butuh proteksi
- [ ] Query pakai Eloquent, bukan raw SQL
- [ ] Sudah dites dengan minimal 2 akun (admin & user)
- [ ] Tampilan sudah sesuai design token (warna, font, hard shadow, dst.)

## 6. Kalau AI Agent Ragu
Kalau ada instruksi yang ambigu atau berpotensi menyimpang dari 6 file `.md` ini (spec, roadmap, concept, flow, business logic, rules), **tanyakan ke user dulu** sebelum eksekusi besar — terutama kalau menyangkut penggantian tech stack atau penambahan scope baru di luar 17 halaman yang sudah disepakati.
