<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Koleksi Saya</h1>
    </x-slot>

    <div class="section">
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
                <div class="space-y-4">
                    @foreach ($diary as $item)
                        <div class="card p-4 flex gap-4">
                            <a href="{{ route('movies.show', $item->movie) }}" class="shrink-0">
                                <div class="w-16 h-24 border-1.5 border-ink rounded-md overflow-hidden">
                                    @if ($item->movie->poster)
                                        <img src="{{ $item->movie->poster }}" alt="{{ $item->movie->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full poster-placeholder flex items-center justify-center bg-gray-100">
                                            <span class="font-display font-bold text-[8px] text-gray-300 transform -rotate-45">FC</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('movies.show', $item->movie) }}">
                                            <h4 class="font-display font-bold text-ink hover:underline">{{ $item->movie->title }}</h4>
                                        </a>
                                        <p class="text-xs text-ink-secondary">Ditonton: {{ $item->watched_date->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('diary.remove', $item->movie) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach ($item->movie->genres->take(3) as $genre)
                                        <span class="badge-blue text-[10px]">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
