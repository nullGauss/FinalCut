<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Koleksi Saya</h1>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Koleksi Saya'],
        ]" />

        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="flex gap-2 mb-6">
            <a href="{{ route('collection.index', ['tab' => 'watchlist']) }}"
               class="btn {{ $tab === 'watchlist' ? 'btn-primary' : 'btn-outline' }}">
                Watchlist ({{ $watchlist->count() }})
            </a>
            <a href="{{ route('collection.index', ['tab' => 'diary']) }}"
               class="btn {{ $tab === 'diary' ? 'btn-primary' : 'btn-outline' }}">
                Diary ({{ $diary->count() }})
            </a>
        </div>

        <!-- Watchlist Tab -->
        @if ($tab === 'watchlist')
            @if ($watchlist->isEmpty())
                <div class="card p-12 text-center border-dashed">
                    <div class="text-4xl mb-4">🎬</div>
                    <h3 class="font-display font-bold text-lg text-ink mb-2">Belum ada watchlist</h3>
                    <p class="text-ink-secondary text-sm mb-4">Tambahkan film yang mau kamu tonton nanti.</p>
                    <a href="{{ route('movies.index') }}" class="btn btn-primary btn-sm">Browse Film</a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($watchlist as $item)
                        <div class="group relative">
                            <a href="{{ route('movies.show', $item->movie) }}" class="block">
                                <div class="aspect-[2/3] w-full border-1.5 border-ink shadow-hard-sm rounded-md overflow-hidden relative">
                                    @if ($item->movie->poster)
                                        <img src="{{ $item->movie->poster }}" alt="{{ $item->movie->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                             onerror="this.outerHTML='<div class=\'w-full h-full poster-placeholder flex items-center justify-center bg-gray-100\'><span class=\'font-display font-bold text-xs text-gray-300 transform -rotate-45\'>FinalCut</span></div>'">
                                    @else
                                        <div class="w-full h-full poster-placeholder flex items-center justify-center bg-gray-100">
                                            <span class="font-display font-bold text-xs text-gray-300 transform -rotate-45">FinalCut</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="mt-2">
                                <a href="{{ route('movies.show', $item->movie) }}" class="block">
                                    <h4 class="font-display font-bold text-sm text-ink line-clamp-1 hover:underline">{{ $item->movie->title }}</h4>
                                </a>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach ($item->movie->genres->take(2) as $genre)
                                        <span class="badge-blue text-[9px]">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <form method="POST" action="{{ route('watchlist.remove', $item->movie) }}" class="absolute top-1 right-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-6 h-6 bg-ink/80 text-white rounded-full text-xs hover:bg-red-600 transition-colors flex items-center justify-center"
                                        title="Hapus dari watchlist">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <!-- Diary Tab -->
        @if ($tab === 'diary')
            @if ($diary->isEmpty())
                <div class="card p-12 text-center border-dashed">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="font-display font-bold text-lg text-ink mb-2">Belum ada diary</h3>
                    <p class="text-ink-secondary text-sm mb-4">Tandai film yang sudah kamu tonton.</p>
                    <a href="{{ route('movies.index') }}" class="btn btn-primary btn-sm">Browse Film</a>
                </div>
            @else
                <!-- Sort Dropdown -->
                <div class="flex items-center gap-2 mb-4">
                    <label class="text-sm text-ink-secondary font-medium">Urutkan:</label>
                    <select onchange="window.location.href=this.value" class="input py-1.5 text-sm w-auto">
                        <option value="{{ route('collection.index', ['tab' => 'diary', 'sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Paling Baru Ditambahkan</option>
                        <option value="{{ route('collection.index', ['tab' => 'diary', 'sort' => 'oldest']) }}" {{ $sort === 'oldest' ? 'selected' : '' }}>Paling Lama Ditambahkan</option>
                        <option value="{{ route('collection.index', ['tab' => 'diary', 'sort' => 'rating_high']) }}" {{ $sort === 'rating_high' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="{{ route('collection.index', ['tab' => 'diary', 'sort' => 'rating_low']) }}" {{ $sort === 'rating_low' ? 'selected' : '' }}>Rating Terendah</option>
                        <option value="{{ route('collection.index', ['tab' => 'diary', 'sort' => 'az']) }}" {{ $sort === 'az' ? 'selected' : '' }}>A - Z</option>
                    </select>
                </div>

                <div class="space-y-3">
                    @foreach ($diary as $item)
                        @php
                            $review = $item->movie->reviews->first();
                            $hasRating = $review && $review->rating;
                            $hasReview = $review && $review->review_text;
                        @endphp
                        <div class="card p-4 flex gap-4">
                            <!-- Poster -->
                            <a href="{{ route('movies.show', $item->movie) }}" class="shrink-0">
                                <div class="w-14 h-20 border-1.5 border-ink rounded-md overflow-hidden">
                                    @if ($item->movie->poster)
                                        <img src="{{ $item->movie->poster }}" alt="{{ $item->movie->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full poster-placeholder flex items-center justify-center bg-gray-100">
                                            <span class="font-display font-bold text-[7px] text-gray-300 transform -rotate-45">FC</span>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <!-- Tanggal -->
                                    <div class="text-center shrink-0 w-10">
                                        <div class="text-[10px] text-ink-secondary uppercase leading-none">{{ $item->watched_date->format('M') }}</div>
                                        <div class="font-display font-bold text-lg leading-tight text-ink">{{ $item->watched_date->format('d') }}</div>
                                        <div class="text-[10px] text-ink-secondary leading-none">{{ $item->watched_date->format('Y') }}</div>
                                    </div>

                                    <!-- Film Info -->
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('movies.show', $item->movie) }}">
                                            <h4 class="font-display font-bold text-sm text-ink hover:underline line-clamp-1">{{ $item->movie->title }}</h4>
                                        </a>
                                        <p class="text-[10px] text-ink-secondary">{{ $item->movie->release_date ? \Carbon\Carbon::parse($item->movie->release_date)->format('Y') : '-' }}</p>
                                    </div>

                                    <!-- Rating -->
                                    <div class="shrink-0 w-20 text-center">
                                        @if ($hasRating)
                                            <div class="flex items-center justify-center gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <svg class="w-3.5 h-3.5 text-green-500 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                    @endif
                                                @endfor
                                            </div>
                                        @else
                                            <span class="text-[10px] text-ink-secondary">-</span>
                                        @endif
                                    </div>

                                    <!-- Review Indicator -->
                                    <div class="shrink-0 w-8 text-center">
                                        @if ($hasReview)
                                            <div class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-bg" title="Sudah diulas">
                                                <svg class="w-4 h-4 text-blue-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        @else
                                            <span class="text-[10px] text-ink-secondary">-</span>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="shrink-0 flex items-center gap-2">
                                        <a href="{{ route('movies.show', $item->movie) }}" class="text-ink-secondary hover:text-ink" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="openDeleteDiaryModal('{{ $item->movie->id }}', '{{ addslashes($item->movie->title) }}')" class="text-ink-secondary hover:text-red-600" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    <!-- Modal Hapus Diary -->
    <div id="deleteDiaryModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDeleteDiaryModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 class="font-display font-bold text-lg text-ink">Hapus dari Diary?</h2>
                <button onclick="closeDeleteDiaryModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-red-50 border-1.5 border-red-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-ink" id="deleteDiaryText">Film ini akan dihapus dari diary kamu.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeDeleteDiaryModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <form id="deleteDiaryForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white w-full">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDeleteDiaryModal(movieId, movieTitle) {
            const form = document.getElementById('deleteDiaryForm');
            form.action = `/movies/${movieId}/diary`;
            document.getElementById('deleteDiaryText').textContent = `"${movieTitle}" akan dihapus dari diary kamu.`;
            document.getElementById('deleteDiaryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteDiaryModal() {
            document.getElementById('deleteDiaryModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
