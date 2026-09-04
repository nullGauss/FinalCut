<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Profile</h1>
    </x-slot>

    <div class="section">
        @if ($user->id === auth()->id())
            <x-breadcrumb :items="[
                ['label' => 'Profile'],
            ]" />
        @else
            <x-breadcrumb :items="[
                ['label' => 'Film', 'url' => route('movies.index')],
                ['label' => 'Profile: ' . $user->name],
            ]" />
        @endif

        <div class="max-w-2xl mx-auto">
            <!-- Profile Card -->
            <div class="card p-8 text-center mb-6">
                <!-- Avatar -->
                <div class="mb-4">
                    @if ($user->foto)
                        <img src="{{ asset('uploads/avatars/' . $user->foto) }}" alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full border-2 border-ink object-cover mx-auto">
                    @else
                        <div class="w-24 h-24 rounded-full border-2 border-ink bg-blue-bg text-blue-text flex items-center justify-center font-display font-bold text-4xl mx-auto">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Name -->
                <h2 class="font-display font-bold text-2xl text-ink mb-1">{{ $user->name }}</h2>

                <!-- Role badge (hanya admin yang lihat) -->
                @if (auth()->user()->role === 'admin' && $user->role === 'admin')
                    <span class="badge-ink text-xs mb-2">Admin</span>
                @endif

                <!-- Bio -->
                @if ($user->bio)
                    <p class="text-sm text-ink-secondary mt-2 mb-4 max-w-xs mx-auto">{{ $user->bio }}</p>
                @else
                    @if ($user->id === auth()->id())
                        <p class="text-sm text-ink-secondary/50 mt-2 mb-4 italic">Belum ada bio. <a href="{{ route('profile.settings') }}" class="text-blue-text hover:underline">Tambahkan sekarang</a></p>
                    @else
                        <p class="text-sm text-ink-secondary/50 mt-2 mb-4 italic">Tidak ada bio</p>
                    @endif
                @endif

                <!-- Joined -->
                <p class="text-xs text-ink-secondary">Bergabung {{ $user->created_at->locale('id')->isoFormat('D MMMM Y') }}</p>

                <!-- Edit Profile Button (hanya owner) -->
                @if ($user->id === auth()->id())
                    <a href="{{ route('profile.settings') }}" class="btn btn-outline btn-sm mt-6">
                        Pengaturan Profile
                    </a>
                @endif
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3 mb-8">
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['reviews'] }}</div>
                    <div class="text-xs text-ink-secondary">Review</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['diary'] }}</div>
                    <div class="text-xs text-ink-secondary">Film Ditonton</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['watchlist'] }}</div>
                    <div class="text-xs text-ink-secondary">Watchlist</div>
                </div>
            </div>

            @php
                $userReviews = $user->reviews()->with('movie')->latest()->take(5)->get();
                $userWatchlist = $user->watchlist()->with('movie')->latest()->take(10)->get();
                $userDiary = $user->watchedDiary()->with('movie')->latest()->take(10)->get();
            @endphp

            <!-- Recent Diary / Films Ditonton -->
            @if ($userDiary->count() > 0)
                <div class="mb-8">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Film Ditonton Terbaru</h3>
                    <div class="grid grid-cols-5 sm:grid-cols-5 gap-3">
                        @foreach ($userDiary as $entry)
                            <a href="{{ route('movies.show', $entry->movie) }}" class="group block">
                                <div class="aspect-[2/3] border-1.5 border-ink rounded-md overflow-hidden shadow-hard-sm group-hover:shadow-hard-md transition-shadow relative">
                                    @if ($entry->movie->poster)
                                        <img src="{{ $entry->movie->poster }}" alt="{{ $entry->movie->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-background flex items-center justify-center">
                                            <span class="font-display font-bold text-[10px] text-gray-300 transform -rotate-45">FinalCut</span>
                                        </div>
                                    @endif
                                    <!-- Rating badge -->
                                    @if ($entry->rating)
                                        <div class="absolute top-1 right-1">
                                            <span class="badge-yellow text-[8px] px-1 py-0.5">★ {{ $entry->rating }}</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-[10px] text-ink-secondary mt-1 line-clamp-1 text-center">{{ $entry->movie->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Watchlist -->
            @if ($userWatchlist->count() > 0)
                <div class="mb-8">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Watchlist Terbaru</h3>
                    <div class="grid grid-cols-5 sm:grid-cols-5 gap-3">
                        @foreach ($userWatchlist as $entry)
                            <a href="{{ route('movies.show', $entry->movie) }}" class="group block">
                                <div class="aspect-[2/3] border-1.5 border-ink rounded-md overflow-hidden shadow-hard-sm group-hover:shadow-hard-md transition-shadow">
                                    @if ($entry->movie->poster)
                                        <img src="{{ $entry->movie->poster }}" alt="{{ $entry->movie->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-background flex items-center justify-center">
                                            <span class="font-display font-bold text-[10px] text-gray-300 transform -rotate-45">FinalCut</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-[10px] text-ink-secondary mt-1 line-clamp-1 text-center">{{ $entry->movie->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Reviews -->
            @if ($userReviews->count() > 0)
                <div class="mb-8">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Ulasan Terbaru</h3>
                    <div class="space-y-3">
                        @foreach ($userReviews as $review)
                            <a href="{{ route('movies.show', $review->movie) }}" class="card-sm p-4 block hover:border-ink transition-colors">
                                <div class="flex items-start gap-3">
                                    <!-- Poster kecil -->
                                    <div class="w-10 h-14 border border-ink rounded overflow-hidden shrink-0">
                                        @if ($review->movie->poster)
                                            <img src="{{ $review->movie->poster }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-background flex items-center justify-center">
                                                <span class="font-display text-[8px] text-gray-300 transform -rotate-45">FC</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-bold text-sm text-ink">{{ $review->movie->title }}</span>
                                            <span class="badge-yellow text-[10px]">★ {{ $review->rating }}/5</span>
                                        </div>
                                        @if ($review->review_text)
                                            <p class="text-xs text-ink-secondary line-clamp-2">{{ $review->review_text }}</p>
                                        @endif
                                        <p class="text-[10px] text-ink-secondary/60 mt-1">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty state -->
            @if ($userReviews->count() === 0 && $userWatchlist->count() === 0 && $userDiary->count() === 0)
                <div class="card p-12 text-center border-dashed">
                    <p class="text-ink-secondary">
                        @if ($user->id === auth()->id())
                            Mulai menjelajah film dan tambahkan ke koleksi kamu!
                        @else
                            Belum ada aktivitas dari user ini.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
