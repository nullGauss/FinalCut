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
                                <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.outerHTML='<div class=\'w-full h-full poster-placeholder flex items-center justify-center bg-gray-100\'><span class=\'font-display font-bold text-xl text-gray-300 transform -rotate-45\'>FinalCut</span></div>'">
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
                            @if ($hasShowtimes)
                                <a href="{{ route('bookings.selectShowtime', $movie) }}" class="btn btn-primary">
                                    Pesan Tiket
                                </a>
                            @endif
                            <form method="POST" action="{{ route('watchlist.toggle', $movie) }}">
                                @csrf
                                <button type="submit" class="btn {{ $isWatchlisted ? 'btn-primary' : 'btn-outline' }}">
                                    {{ $isWatchlisted ? '✓ Watchlist' : '+ Watchlist' }}
                                </button>
                            </form>
                            <button type="button" onclick="openDiaryModal()" class="btn btn-outline">
                                {{ $isDiaried ? '✓ Diary' : '+ Diary' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Trailer -->
                @if ($movie->trailer_url)
                    <div class="card p-6">
                        <h3 class="font-display font-bold text-xl text-ink mb-4">Trailer</h3>
                        <div class="aspect-video w-full rounded-md overflow-hidden border-1.5 border-ink bg-black">
                            @php
                                $videoId = null;
                                $url = $movie->trailer_url;
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                                    $videoId = $matches[1];
                                }
                            @endphp
                            @if ($videoId)
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                        class="w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-white">
                                    <a href="{{ $movie->trailer_url }}" target="_blank" class="text-sm underline hover:text-gray-300">Tonton di YouTube</a>
                                </div>
                            @endif
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

    <!-- Modal Tambah Diary -->
    <div id="diaryModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDiaryModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 class="font-display font-bold text-lg text-ink">Tanggal Nonton</h2>
                <button onclick="closeDiaryModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('diary.add', $movie) }}" class="p-6">
                @csrf
                <div class="mb-6">
                    <label for="watched_date" class="label">Kapan kamu nonton film ini? <span class="text-red-600">*</span></label>
                    <input type="date" id="watched_date" name="watched_date" class="input" max="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDiaryModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm flex-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDiaryModal() {
            document.getElementById('diaryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDiaryModal() {
            document.getElementById('diaryModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
