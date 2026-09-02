<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FinalCut') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&family=Indie+Flower&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-container font-body">
    <!-- Header -->
    <header class="border-b border-ink bg-surface sticky top-0 z-50">
        <div class="section flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2" aria-label="FinalCut Home">
                <span class="font-display font-bold text-xl text-ink">FinalCut</span>
                <span class="font-handwriting text-sm text-ink-secondary">film & ticket</span>
            </a>

            <!-- Nav -->
            <nav class="hidden md:flex items-center gap-6">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-outline btn-sm">
                            Log Out
                        </x-dropdown-link>
                    </form>
                @endguest
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="section relative overflow-hidden">
            <div class="absolute inset-0 poster-placeholder opacity-5" aria-hidden="true"></div>
            <div class="relative grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="handwriting text-2xl text-ink-secondary mb-4 block">Selamat datang di</span>
                    <h1 class="font-display font-bold text-5xl lg:text-6xl leading-tight text-ink mb-6">
                        Final<span class="text-blue-text">Cut</span>
                    </h1>
                    <p class="text-lg text-ink-secondary mb-8 max-w-xl">
                        Platform pertama yang gabungin database film + ulasan komunitas <span class="font-medium">ala Letterboxd</span> dengan booking tiket bioskop <span class="font-medium">ala Tix.id</span>. Satu app, nonton & review tanpa app-switching.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            Mulai Gratis
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">
                            Masuk
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-[4/5] bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden relative poster-placeholder">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-24 h-24 text-ink-secondary opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A2.25 2.25 0 006 7.5h2.25M3.75 4.5h.75m-.75 0h.008v.008H12V4.5h.75m0 0h.008v.008H12V4.5h.75m0 0h.008v.008H21v.75a2.25 2.25 0 01-2.25 2.25h-2.25m0 0h.75m-.75 0h.008v.008H12v.75h.75m0 0h.008v.008H12v.75h.75m0 0h.008v.008H3.75v.75m0 0h.008v.008H12v.75h.75M12 18h1.5m-1.5 0h.008v.008h-.008V18zm0-2.25h1.5m-1.5 0h.008v.008h-.008V15.75zm0-2.25h1.5m-1.5 0h.008v.008h-.008V13.5zm0-2.25h1.5m-1.5 0h.008v.008h-.008V11.25zm0-2.25h1.5m-1.5 0h.008v.008h-.008V9z" />
                            </svg>
                        </div>
                        <div class="obi-strip">FinalCut</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="section bg-surface border-y border-ink">
            <h2 class="font-display font-bold text-3xl text-center mb-12">Dua Pilar Utama</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Cinephile Pillar -->
                <article class="card p-8 hover:shadow-hard-lg transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-blue-bg text-blue-text rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-xl">Cinephile Pillar</h3>
                    </div>
                    <p class="text-ink-secondary mb-6">
                        Cari film, baca & tulis ulasan, rating 1-5 bintang, watchlist "mau ditonton", diary "sudah ditonton". Komunitas penonton Indonesia.
                    </p>
                    <ul class="space-y-2 text-sm text-ink-secondary">
                        <li class="flex items-center gap-2">✓ Database film lengkap (sinopsis, cast, trailer)</li>
                        <li class="flex items-center gap-2">✓ Ulasan & rating komunitas</li>
                        <li class="flex items-center gap-2">✓ Watchlist & Watched Diary</li>
                        <li class="flex items-center gap-2">✓ Filter genre & pencarian</li>
                    </ul>
                </article>

                <!-- Booking Pillar -->
                <article class="card p-8 hover:shadow-hard-lg transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 12a7.975 7.975 0 01-2.343 6.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-xl">Booking Pillar</h3>
                    </div>
                    <p class="text-ink-secondary mb-6">
                        Lihat jadwal tayang di bioskop terdekat, pilih kursi interaktif, checkout pembayaran, dapat e-tiket. Alur lengkap dari pilih film sampai duduk di bioskop.
                    </p>
                    <ul class="space-y-2 text-sm text-ink-secondary">
                        <li class="flex items-center gap-2">✓ Pilih bioskop & studio</li>
                        <li class="flex items-center gap-2">✓ Seat map interaktif</li>
                        <li class="flex items-center gap-2">✓ Simulasi pembayaran</li>
                        <li class="flex items-center gap-2">✓ Riwayat transaksi</li>
                    </ul>
                </article>
            </div>
        </section>

        <!-- CTA -->
        <section class="section text-center">
            <h2 class="font-display font-bold text-3xl mb-4">Siap Nonton & Review?</h2>
            <p class="text-ink-secondary mb-8 max-w-2xl mx-auto">
                Gabung ribuan penonton Indonesia yang sudah pakai FinalCut untuk cari film favorit & booking tiket bioskop dalam satu app.
            </p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-ink bg-surface py-8">
        <div class="section flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-ink-secondary">&copy; {{ date('Y') }} FinalCut. Dibuat untuk tugas mata kuliah pemrograman web.</p>
            <div class="flex items-center gap-4 text-sm text-ink-secondary">
                <span class="handwriting">FinalCut</span>
                <span>film & ticket</span>
            </div>
        </div>
    </footer>
</body>
</html>