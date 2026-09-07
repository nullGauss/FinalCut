<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Detail Film</h1>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline btn-sm">Kembali</a>
        </div>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Kelola Film', 'url' => route('admin.movies.index')],
            ['label' => $movie->title],
        ]" />

        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Movie Info -->
        <div class="card p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Poster -->
                <div class="flex-shrink-0">
                    @if ($movie->poster)
                        <img src="{{ $movie->poster }}" alt="{{ $movie->title }}"
                             class="w-48 h-72 object-cover border-1.5 border-ink shadow-hard-md"
                             onerror="this.outerHTML='<div class=\'w-48 h-72 bg-gray-100 border-1.5 border-ink shadow-hard-md flex items-center justify-center\'><span class=\'font-display font-bold text-xl text-gray-300 transform -rotate-45\'>No Poster</span></div>'">
                    @else
                        <div class="w-48 h-72 bg-gray-100 border-1.5 border-ink shadow-hard-md flex items-center justify-center">
                            <span class="font-display font-bold text-xl text-gray-300 transform -rotate-45">No Poster</span>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="flex-1 space-y-3">
                    <h2 class="font-display font-bold text-3xl text-ink">{{ $movie->title }}</h2>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($movie->genres as $genre)
                            <span class="badge-blue">{{ $genre->name }}</span>
                        @endforeach
                        <span class="badge-yellow">{{ $movie->rating_umur }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="font-bold text-ink-secondary">Sutradara:</span>
                            <span class="text-ink">{{ $movie->director ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-ink-secondary">Durasi:</span>
                            <span class="text-ink">{{ $movie->durasi ? $movie->durasi . ' menit' : '-' }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-ink-secondary">Rilis:</span>
                            <span class="text-ink">{{ $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-ink-secondary">Rating:</span>
                            <span class="text-ink">{{ $avgRating ? number_format($avgRating, 1) : '-' }} / 5 ({{ $totalReviews }} ulasan)</span>
                        </div>
                    </div>

                    @if ($movie->sinopsis)
                        <div>
                            <span class="font-bold text-ink-secondary text-sm">Sinopsis:</span>
                            <p class="text-ink text-sm mt-1">{{ $movie->sinopsis }}</p>
                        </div>
                    @endif

                    @if ($movie->trailer_url)
                        <div>
                            <span class="font-bold text-ink-secondary text-sm">Trailer:</span>
                            <a href="{{ $movie->trailer_url }}" target="_blank" class="text-blue-text text-sm hover:underline ml-1">{{ $movie->trailer_url }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="card">
            <div class="px-6 py-4 border-b-1.5 border-ink">
                <h3 class="font-display font-bold text-lg text-ink">Ulasan Pengguna ({{ $totalReviews }})</h3>
            </div>

            @if ($movie->reviews->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach ($movie->reviews as $review)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
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
                                            <span class="font-medium text-ink text-sm">{{ $review->user->name }}</span>
                                            <span class="text-xs text-ink-secondary">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <!-- Rating -->
                                        <div class="flex items-center gap-0.5 mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <svg class="w-4 h-4 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                @endif
                                            @endfor
                                            <span class="ml-1 text-xs font-bold text-ink">{{ $review->rating }}/5</span>
                                        </div>
                                        @if ($review->review_text)
                                            <p class="text-sm text-ink mt-1">{{ $review->review_text }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.movies.reviews.destroy', [$movie, $review]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white flex-shrink-0" title="Hapus Ulasan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-ink-secondary">
                    Belum ada ulasan dari pengguna.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
