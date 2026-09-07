<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Dashboard</h1>
            <span class="badge-blue">User</span>
        </div>
    </x-slot>

    <div class="section">
        <!-- Welcome Banner -->
        <div class="card p-8 mb-8">
            <p class="handwriting text-xl text-ink-secondary mb-2">Selamat datang di FinalCut~</p>
            <h2 class="font-display font-bold text-3xl text-ink mb-2">
                Halo, {{ Auth::user()->name }}!
            </h2>
            <p class="text-ink-secondary">
                Mau nonton apa hari ini? Cari film favoritmu, baca ulasan dari komunitas, atau langsung booking tiket bioskop.
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="card p-4 text-center">
                <div class="font-display font-bold text-2xl text-ink">{{ $stats['reviews'] }}</div>
                <div class="text-xs text-ink-secondary">Review</div>
            </div>
            <div class="card p-4 text-center">
                <div class="font-display font-bold text-2xl text-ink">{{ $stats['watchlist'] }}</div>
                <div class="text-xs text-ink-secondary">Watchlist</div>
            </div>
            <div class="card p-4 text-center">
                <div class="font-display font-bold text-2xl text-ink">{{ $stats['diary'] }}</div>
                <div class="text-xs text-ink-secondary">Film Ditonton</div>
            </div>
            <div class="card p-4 text-center">
                <div class="font-display font-bold text-2xl text-ink">{{ $stats['bookings'] }}</div>
                <div class="text-xs text-ink-secondary">Booking</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h3 class="font-display font-bold text-lg text-ink mb-4">Menu Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('movies.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Browse Film</h4>
                <p class="text-sm text-ink-secondary">Cari & lihat detail film favoritmu</p>
            </a>

            <a href="{{ route('collection.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Watchlist</h4>
                <p class="text-sm text-ink-secondary">Film yang mau kamu tonton</p>
            </a>

            <a href="{{ route('movies.nowShowing') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Booking Tiket</h4>
                <p class="text-sm text-ink-secondary">Pesan tiket bioskop sekarang</p>
            </a>
        </div>

        <!-- Spotlight Film -->
        @if ($spotlight->count() > 0)
            <div class="mb-8">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Spotlight</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($spotlight as $movie)
                        <a href="{{ route('movies.show', $movie) }}" class="card overflow-hidden group hover:shadow-hard-lg transition-all">
                            <div class="flex">
                                <div class="w-28 h-44 flex-shrink-0 border-r-1.5 border-ink overflow-hidden">
                                    @if ($movie->poster)
                                        <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-background flex items-center justify-center">
                                            <span class="font-display font-bold text-sm text-gray-300 transform -rotate-45">FinalCut</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        @if ($movie->genres->count() > 0)
                                            <span class="badge-blue text-xs">{{ $movie->genres->first()->name }}</span>
                                        @endif
                                        @if ($movie->durasi)
                                            <span class="text-xs text-ink-secondary">{{ $movie->durasi }} menit</span>
                                        @endif
                                    </div>
                                    <h4 class="font-display font-bold text-lg text-ink mb-1 group-hover:text-blue-text transition-colors">{{ $movie->title }}</h4>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($movie->reviews->avg('rating') ?? 0))
                                                    <svg class="w-3 h-3 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                @else
                                                    <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-ink-secondary">{{ number_format($movie->reviews->avg('rating') ?? 0, 1) }} ({{ $movie->reviews_count }} ulasan)</span>
                                    </div>
                                    @if ($movie->sinopsis)
                                        <p class="text-xs text-ink-secondary mt-2 line-clamp-2">{{ Str::limit($movie->sinopsis, 100) }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Now Showing -->
        @if ($nowShowing->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display font-bold text-lg text-ink">Sedang Tayang</h3>
                    <a href="{{ route('movies.nowShowing') }}" class="text-sm text-blue-text hover:underline">Lihat Semua</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach ($nowShowing as $movie)
                        <a href="{{ route('movies.show', $movie) }}" class="group block">
                            <div class="aspect-[2/3] border-1.5 border-ink rounded-md overflow-hidden shadow-hard-sm group-hover:shadow-hard-md transition-shadow">
                                @if ($movie->poster)
                                    <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-background flex items-center justify-center">
                                        <span class="font-display font-bold text-xs text-gray-300 transform -rotate-45">FinalCut</span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-ink mt-2 line-clamp-1 font-medium">{{ $movie->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ulasan Populer dari Komunitas -->
        @if ($popularReviews->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display font-bold text-lg text-ink">Ulasan Populer dari Komunitas</h3>
                </div>
                <div class="space-y-3">
                    @foreach ($popularReviews as $review)
                        <div class="card-sm p-4 hover:border-ink transition-colors">
                            <div class="flex items-start gap-3">
                                <!-- Avatar -->
                                <div class="w-9 h-9 rounded-full border-1.5 border-ink flex-shrink-0 overflow-hidden bg-blue-bg flex items-center justify-center">
                                    @if ($review->user->foto)
                                        <img src="{{ asset('uploads/avatars/' . $review->user->foto) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="font-display font-bold text-sm text-blue-text">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('profile.user', $review->user) }}" class="font-medium text-sm text-ink hover:text-blue-text transition-colors">{{ $review->user->name }}</a>
                                        <span class="text-xs text-ink-secondary">menulis ulasan untuk</span>
                                        <a href="{{ route('movies.show', $review->movie) }}" class="font-medium text-sm text-blue-text hover:underline">{{ $review->movie->title }}</a>
                                    </div>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <svg class="w-3 h-3 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                            @else
                                                <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                            @endif
                                        @endfor
                                    </div>
                                    @if ($review->review_text)
                                        <p class="text-sm text-ink mt-1 line-clamp-2">{{ $review->review_text }}</p>
                                    @endif
                                    <span class="text-xs text-ink-secondary mt-1 block">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Koleksi Terakhir -->
        @if ($recentCollection->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display font-bold text-lg text-ink">Koleksi Terakhir</h3>
                    <a href="{{ route('collection.index') }}" class="text-sm text-blue-text hover:underline">Lihat Semua</a>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($recentCollection as $item)
                        <a href="{{ route('movies.show', $item['movie']) }}" class="group block relative">
                            <div class="aspect-[2/3] border-1.5 border-ink rounded-md overflow-hidden shadow-hard-sm group-hover:shadow-hard-md transition-shadow">
                                @if ($item['movie']->poster)
                                    <img src="{{ $item['movie']->poster }}" alt="{{ $item['movie']->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-background flex items-center justify-center">
                                        <span class="font-display font-bold text-xs text-gray-300 transform -rotate-45">FinalCut</span>
                                    </div>
                                @endif
                            </div>
                            <!-- Type Badge -->
                            <div class="absolute top-1 left-1">
                                @if ($item['type'] === 'watchlist')
                                    <span class="badge-blue text-[10px] py-0 px-1.5">Watchlist</span>
                                @else
                                    <span class="badge-yellow text-[10px] py-0 px-1.5">Diary</span>
                                @endif
                            </div>
                            <p class="text-xs text-ink mt-2 line-clamp-1 font-medium">{{ $item['movie']->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Bookings -->
        @if ($recentBookings->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display font-bold text-lg text-ink">Booking Terakhir</h3>
                    <a href="{{ route('bookings.history') }}" class="text-sm text-blue-text hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @foreach ($recentBookings as $booking)
                        <a href="{{ route('bookings.show', $booking) }}" class="card-sm p-4 block hover:border-ink transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-sm text-ink">{{ $booking->showtime->movie->title }}</span>
                                    <p class="text-xs text-ink-secondary">{{ $booking->showtime->studio->cinema->name }} &middot; {{ $booking->showtime->show_date->format('d M Y') }}</p>
                                </div>
                                @if ($booking->status === 'paid')
                                    <span class="badge-green">Paid</span>
                                @elseif ($booking->status === 'cancelled')
                                    <span class="badge-red">Cancelled</span>
                                @else
                                    <span class="badge-yellow">Pending</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
