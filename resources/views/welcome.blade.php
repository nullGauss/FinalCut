<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FinalCut') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&family=Indie+Flower&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
    <style>
        html.lenis, html.lenis body {
            height: auto;
        }
        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }
        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }
        .lenis.lenis-stopped {
            overflow: hidden;
        }
        /* ===== Welcome Animations ===== */
        [data-anim] {
            opacity: 0;
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        [data-anim="fade-up"] {
            transform: translateY(32px);
        }
        [data-anim="fade-down"] {
            transform: translateY(-32px);
        }
        [data-anim="fade-left"] {
            transform: translateX(-40px);
        }
        [data-anim="fade-right"] {
            transform: translateX(40px);
        }
        [data-anim="scale-in"] {
            transform: scale(0.9);
        }
        [data-anim].is-visible {
            opacity: 1;
            transform: translateY(0) translateX(0) scale(1);
        }

        /* Hero entrance */
        .hero-text {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .hero-text.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .hero-ticket {
            opacity: 0;
            transform: translateX(40px) rotate(2deg);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .hero-ticket.is-visible {
            opacity: 1;
            transform: translateX(0) rotate(0deg);
        }

        /* Floating cards */
        .floating-card {
            animation: float-bob 3s ease-in-out infinite alternate;
        }
        @keyframes float-bob {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-8px); }
        }

        /* Staggered children */
        [data-stagger] > * {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        [data-stagger].is-visible > *:nth-child(1) { transition-delay: 0s; }
        [data-stagger].is-visible > *:nth-child(2) { transition-delay: 0.12s; }
        [data-stagger].is-visible > *:nth-child(3) { transition-delay: 0.24s; }
        [data-stagger].is-visible > *:nth-child(4) { transition-delay: 0.36s; }
        [data-stagger].is-visible > * {
            opacity: 1;
            transform: translateY(0);
        }

        /* Step number pulse */
        .step-circle {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .step-circle:hover {
            transform: scale(1.1);
            box-shadow: 3px 3px 0 #111111;
        }

        /* Card hover lift */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 4px 4px 0 #111111;
        }

        /* Review card slide in */
        .review-card {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .review-card.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .review-card.is-visible:nth-child(1) { transition-delay: 0s; }
        .review-card.is-visible:nth-child(2) { transition-delay: 0.15s; }
        .review-card.is-visible:nth-child(3) { transition-delay: 0.3s; }

        /* Stats pop in */
        .stat-item {
            opacity: 0;
            transform: scale(0.8);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .stat-item.is-visible {
            opacity: 1;
            transform: scale(1);
        }
        .stat-item.is-visible:nth-child(1) { transition-delay: 0s; }
        .stat-item.is-visible:nth-child(3) { transition-delay: 0.1s; }
        .stat-item.is-visible:nth-child(5) { transition-delay: 0.2s; }
        .stat-item.is-visible:nth-child(7) { transition-delay: 0.3s; }

        /* CTA bounce in */
        .cta-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .cta-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .cta-section.is-visible .btn {
            animation: cta-pop 0.5s ease 0.4s both;
        }
        @keyframes cta-pop {
            0%   { transform: scale(0.9); opacity: 0; }
            60%  { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }

    </style>
</head>
<body class="page-container font-body">
    <!-- Header -->
    <header class="border-b border-ink bg-surface sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="section flex items-center justify-between h-14">
            <a href="/" class="flex items-center gap-2" aria-label="FinalCut Home">
                <span class="font-display font-bold text-xl text-ink">FinalCut</span>
                <span class="font-handwriting text-sm text-ink-secondary hidden sm:inline">film & ticket</span>
            </a>
            <!-- Desktop Nav -->
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
            <!-- Mobile Hamburger -->
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 border-1.5 border-ink rounded-md text-ink hover:bg-ink hover:text-surface transition-all">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Mobile Nav Dropdown -->
        <div :class="{'block': mobileOpen, 'hidden': !mobileOpen}" class="md:hidden border-t border-ink">
            <div class="section py-4 space-y-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm w-full text-center">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm w-full text-center">Daftar</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm w-full text-center">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm w-full text-center">Log Out</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="section relative overflow-hidden">
            <div class="absolute inset-0 poster-placeholder opacity-5" aria-hidden="true"></div>
            <div class="relative grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div class="hero-text text-center lg:text-left">
                    <span class="handwriting text-xl sm:text-2xl text-ink-secondary mb-4 block">Selamat datang di</span>
                    <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-6xl leading-tight text-ink mb-6">
                        Final<span class="text-blue-text">Cut</span>
                    </h1>
                    <p class="text-base sm:text-lg text-ink-secondary mb-8 max-w-xl mx-auto lg:mx-0">
                        Temukan film favoritmu, baca & tulis ulasan dari komunitas penonton, lalu booking tiket bioskop langsung dari satu platform. Nonton jadi lebih mudah.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            Mulai Gratis
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">
                            Login
                        </a>
                    </div>
                </div>

                <!-- Cinema Ticket CSS Art -->
                <div class="hero-ticket relative flex justify-center items-center py-4 overflow-hidden">
                    <div class="relative w-full max-w-[420px]">
                        <!-- Main Ticket -->
                        <div class="w-full bg-surface border-1.5 border-ink shadow-hard-xl overflow-hidden">
                            <!-- Ticket Header -->
                            <div class="bg-ink text-surface px-6 sm:px-10 py-4 sm:py-5 flex items-center justify-between">
                                <span class="font-display font-bold text-2xl sm:text-3xl tracking-tight">FinalCut</span>
                                <span class="handwriting text-sm sm:text-lg opacity-80">e-tiket</span>
                            </div>
                            <!-- Perforated Line -->
                            <div class="border-b-2 border-dashed border-ink/30 mx-4 sm:mx-6"></div>
                            <!-- Ticket Body -->
                            <div class="px-6 sm:px-10 py-6 sm:py-8">
                                <div class="flex items-start justify-between mb-6">
                                    <div>
                                        <div class="handwriting text-base text-ink-secondary mb-1">Film</div>
                                        <div class="font-display font-bold text-2xl sm:text-3xl text-ink leading-tight">Midnight Signals</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="handwriting text-base text-ink-secondary mb-1">Rating</div>
                                        <div class="flex items-center gap-1">
                                            <span class="text-yellow-500 text-xl">&#9733;</span>
                                            <span class="font-display font-bold text-2xl text-ink">4.5</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-4 text-center mb-6">
                                    <div>
                                        <div class="handwriting text-sm text-ink-secondary">Studio</div>
                                        <div class="font-display font-bold text-xl text-ink">3</div>
                                    </div>
                                    <div>
                                        <div class="handwriting text-sm text-ink-secondary">Kursi</div>
                                        <div class="font-display font-bold text-xl text-ink">F7</div>
                                    </div>
                                    <div>
                                        <div class="handwriting text-sm text-ink-secondary">Jam</div>
                                        <div class="font-display font-bold text-xl text-ink">19:30</div>
                                    </div>
                                    <div>
                                        <div class="handwriting text-sm text-ink-secondary">Harga</div>
                                        <div class="font-display font-bold text-xl text-ink">45K</div>
                                    </div>
                                </div>
                                <!-- Barcode -->
                                <div class="flex items-end gap-[2px] justify-center h-12 opacity-40">
                                    @for ($i = 0; $i < 40; $i++)
                                        <div class="bg-ink" style="width: 3px; height: {{ rand(16, 48) }}px;"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Floating Mini Cards (hidden on mobile) -->
                        <div class="floating-card absolute -top-5 -right-12 bg-surface border-1.5 border-ink shadow-hard-lg px-5 py-3 rounded-lg hidden sm:block">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                <span class="font-display font-bold text-sm text-ink">+Watchlist</span>
                            </div>
                        </div>
                        <div class="floating-card absolute -bottom-4 -left-10 bg-surface border-1.5 border-ink shadow-hard-lg px-5 py-3 rounded-lg hidden sm:block">
                            <div class="flex items-center gap-2">
                                <span class="text-yellow-500 text-lg">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                                <span class="font-display font-bold text-sm text-ink">5/5</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="section">
            <div data-anim="fade-up">
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-center mb-4">Dua Pilar Utama</h2>
                <p class="text-center text-ink-secondary mb-12 max-w-xl mx-auto">FinalCut menggabungkan dua kebutuhan penonton dalam satu platform: riset film dan booking tiket.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8" data-stagger>
                <!-- Cinephile Pillar -->
                <article class="card card-hover p-6 sm:p-8">
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
                <article class="card card-hover p-6 sm:p-8">
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

        <!-- How It Works -->
        <section class="section bg-surface border-y border-ink">
            <div data-anim="fade-up">
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-center mb-4">Cara Kerja</h2>
                <p class="text-center text-ink-secondary mb-12 max-w-xl mx-auto">Tiga langkah mudah dari cari film sampai nonton di bioskop.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8" data-stagger>
                <div class="text-center">
                    <div class="step-circle w-14 h-14 border-1.5 border-ink rounded-full flex items-center justify-center mx-auto mb-4 bg-blue-bg cursor-pointer">
                        <span class="font-display font-bold text-xl text-blue-text">1</span>
                    </div>
                    <h4 class="font-display font-bold text-lg mb-2">Cari & Pilih Film</h4>
                    <p class="text-sm text-ink-secondary">Browse film yang sedang tayang, baca ulasan komunitas, cek rating & sinopsis sebelum memutuskan.</p>
                </div>
                <div class="text-center">
                    <div class="step-circle w-14 h-14 border-1.5 border-ink rounded-full flex items-center justify-center mx-auto mb-4 bg-yellow-bg cursor-pointer">
                        <span class="font-display font-bold text-xl text-yellow-text">2</span>
                    </div>
                    <h4 class="font-display font-bold text-lg mb-2">Booking Tiket</h4>
                    <p class="text-sm text-ink-secondary">Pilih bioskop, jadwal tayang, & kursi favoritmu lewat seat map interaktif. Bayar dalam beberapa langkah.</p>
                </div>
                <div class="text-center">
                    <div class="step-circle w-14 h-14 border-1.5 border-ink rounded-full flex items-center justify-center mx-auto mb-4 bg-blue-bg cursor-pointer">
                        <span class="font-display font-bold text-xl text-blue-text">3</span>
                    </div>
                    <h4 class="font-display font-bold text-lg mb-2">Nonton & Review</h4>
                    <p class="text-sm text-ink-secondary">Tonton filmnya, lalu tulis ulasan & rating di FinalCut. Tambahkan ke diary tontonanmu.</p>
                </div>
            </div>
        </section>

        <!-- Latest Reviews -->
        @php
            $latestReviews = \App\Models\Review::with(['user', 'movie'])
                ->whereNotNull('review_text')
                ->latest()
                ->take(3)
                ->get();
        @endphp
        @if ($latestReviews->count() > 0)
            <section class="section">
                <div data-anim="fade-up">
                    <h2 class="font-display font-bold text-2xl sm:text-3xl text-center mb-4">Ulasan Terbaru</h2>
                    <p class="handwriting text-center text-ink-secondary mb-12">ulasan dari sesama penonton~</p>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach ($latestReviews as $review)
                        <div class="review-card card card-hover p-6" data-anim="fade-up">
                            <div class="flex items-center gap-2 mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <svg class="w-4 h-4 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-sm text-ink mb-4 line-clamp-3">"{{ Str::limit($review->review_text, 120) }}"</p>
                            <div class="flex items-center justify-between text-xs text-ink-secondary">
                                <span>{{ $review->user->name }}</span>
                                <span>{{ $review->movie->title }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Stats Strip -->
        <section class="border-y border-ink bg-surface">
            @php
                $statMovies = \App\Models\Movie::count();
                $statUsers = \App\Models\User::where('role', 'user')->count();
                $statReviews = \App\Models\Review::count();
                $statBookings = \App\Models\Booking::where('status', 'paid')->count();
            @endphp
            <div class="section flex flex-wrap items-center justify-center gap-6 sm:gap-8 md:gap-16 py-5" data-stagger>
                <div class="stat-item text-center">
                    <div class="font-display font-bold text-lg sm:text-xl text-ink stat-number" data-target="{{ $statMovies }}">0</div>
                    <div class="text-xs text-ink-secondary">Film</div>
                </div>
                <span class="text-gray-300 stat-item hidden sm:inline">/</span>
                <div class="stat-item text-center">
                    <div class="font-display font-bold text-lg sm:text-xl text-ink stat-number" data-target="{{ $statUsers }}">0</div>
                    <div class="text-xs text-ink-secondary">Member</div>
                </div>
                <span class="text-gray-300 stat-item hidden sm:inline">/</span>
                <div class="stat-item text-center">
                    <div class="font-display font-bold text-lg sm:text-xl text-ink stat-number" data-target="{{ $statReviews }}">0</div>
                    <div class="text-xs text-ink-secondary">Ulasan</div>
                </div>
                <span class="text-gray-300 stat-item hidden sm:inline">/</span>
                <div class="stat-item text-center">
                    <div class="font-display font-bold text-lg sm:text-xl text-ink stat-number" data-target="{{ $statBookings }}">0</div>
                    <div class="text-xs text-ink-secondary">Tiket Terjual</div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="section text-center border-t border-ink cta-section" data-anim="fade-up">
            <h2 class="font-display font-bold text-2xl sm:text-3xl mb-4">Siap Nonton & Review?</h2>
            <p class="text-ink-secondary mb-8 max-w-2xl mx-auto">
                Gabung komunitas penonton Indonesia yang sudah pakai FinalCut untuk cari film favorit & booking tiket bioskop dalam satu app.
            </p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
        </section>
    </main>

    <!-- Footer -->
    @include('components.footer')

    <script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>
