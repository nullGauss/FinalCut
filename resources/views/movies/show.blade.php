<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('movies.index') }}" class="btn btn-outline btn-sm">
                &larr; Kembali
            </a>
            <h1 class="font-display font-bold text-2xl text-ink line-clamp-1">{{ $movie->title }}</h1>
        </div>
    </x-slot>

    <div class="section">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="card-sm p-4 mb-6 bg-red-50 border-red-600 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-[1fr_300px] gap-8 items-start">
            <!-- Left Column: Details -->
            <div class="space-y-8">
                <!-- Main Info Card -->
                <div class="card p-6 md:p-8 flex flex-col sm:flex-row gap-6 md:gap-8">
                    <!-- Poster -->
                    <div class="w-full sm:w-48 shrink-0">
                        <div class="aspect-[2/3] w-full border-1.5 border-ink shadow-hard-sm rounded-md overflow-hidden relative">
                            @if ($movie->poster)
                                <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full poster-placeholder flex items-center justify-center bg-gray-100">
                                    <span class="font-display font-bold text-xl text-gray-300 transform -rotate-45">FinalCut</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-1">
                        <h2 class="font-display font-bold text-3xl md:text-4xl text-ink mb-2">{{ $movie->title }}</h2>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="badge-yellow">{{ $movie->rating_umur }}</span>
                            @if($movie->durasi)
                                <span class="badge-ink">{{ $movie->durasi }} menit</span>
                            @endif
                            @if($movie->release_date)
                                <span class="badge-ink">{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-1 mb-6">
                            @foreach ($movie->genres as $genre)
                                <span class="badge-blue">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-display font-bold text-sm text-ink-secondary uppercase tracking-wider mb-1">SUTRADARA</h3>
                                <p class="text-ink">{{ $movie->director ?: '-' }}</p>
                            </div>
                            
                            <div>
                                <h3 class="font-display font-bold text-sm text-ink-secondary uppercase tracking-wider mb-1">SINOPSIS</h3>
                                <p class="text-ink leading-relaxed">{{ $movie->sinopsis ?: 'Belum ada sinopsis.' }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="#" class="btn btn-primary" onclick="alert('Fitur Booking dikerjakan di Minggu 4-5')">
                                Booking Tiket
                            </a>
                            <button class="btn btn-outline" onclick="alert('Fitur Watchlist dikerjakan di Minggu 7')">
                                + Watchlist
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Trailer -->
                @if ($movie->trailer_url)
                    <div class="card p-6">
                        <h3 class="font-display font-bold text-xl text-ink mb-4">Trailer</h3>
                        <div class="aspect-video w-full rounded-md overflow-hidden border-1.5 border-ink bg-black">
                            <!-- Simulating trailer for now, as proper iframe embed requires processing the YouTube URL -->
                            <div class="w-full h-full flex flex-col items-center justify-center text-white">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <a href="{{ $movie->trailer_url }}" target="_blank" class="text-sm underline hover:text-gray-300">Tonton di YouTube</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Rating & Reviews -->
            <div class="space-y-6">
                <!-- Rating Summary -->
                <div class="card p-6 text-center">
                    <h3 class="font-display font-bold text-sm text-ink-secondary uppercase tracking-wider mb-2">RATING KOMUNITAS</h3>
                    <div class="flex items-end justify-center gap-1 mb-1">
                        <span class="font-display font-bold text-5xl text-ink">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</span>
                        <span class="text-ink-secondary mb-1">/ 5</span>
                    </div>
                    <p class="text-sm text-ink-secondary">dari {{ $reviewCount }} ulasan</p>
                </div>

                <!-- Add Review Form -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Tulis Ulasan</h3>
                    <form method="POST" action="{{ route('reviews.store', $movie) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="label">Rating (1-5) <span class="text-red-600">*</span></label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" required>
                                        <div class="w-8 h-8 flex items-center justify-center rounded-full border-1.5 border-ink bg-surface peer-checked:bg-yellow-bg peer-checked:text-yellow-text hover:bg-background transition-colors font-bold">
                                            {{ $i }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            @error('rating') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="review_text" class="label">Ulasan (Opsional)</label>
                            <textarea id="review_text" name="review_text" rows="3" class="input" placeholder="Bagaimana pendapatmu?"></textarea>
                            @error('review_text') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-full justify-center">Kirim Ulasan</button>
                    </form>
                </div>

                <!-- Community Reviews -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-lg text-ink">Ulasan Penonton</h3>
                    
                    @forelse ($movie->reviews as $review)
                        <div class="card-sm p-4">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <div>
                                    <span class="font-bold text-ink">{{ $review->user->name }}</span>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="badge-yellow text-[10px]">★ {{ $review->rating }}/5</span>
                                        <span class="text-[10px] text-ink-secondary">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                @if($review->user_id === auth()->id())
                                    <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                                    </form>
                                @endif
                            </div>
                            @if($review->review_text)
                                <p class="text-sm text-ink leading-relaxed mt-2">{{ $review->review_text }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="card-sm p-6 text-center border-dashed">
                            <p class="text-sm text-ink-secondary">Belum ada ulasan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
