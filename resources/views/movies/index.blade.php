<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Daftar Film</h1>
    </x-slot>

    <div class="section">
        <!-- Search & Filter -->
        <form method="GET" class="card p-4 mb-8">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input flex-1" placeholder="Cari judul film...">
                <select name="genre" class="input w-auto sm:w-48">
                    <option value="">Semua Genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </div>
        </form>

        <!-- Movie Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($movies as $movie)
                <a href="{{ route('movies.show', $movie) }}" class="group block">
                    <!-- Poster -->
                    <div class="aspect-[2/3] w-full card mb-4 overflow-hidden relative">
                        @if ($movie->poster)
                            <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.outerHTML='<div class=\'w-full h-full poster-placeholder flex items-center justify-center bg-gray-100 group-hover:scale-105 transition-transform duration-300\'><span class=\'font-display font-bold text-2xl text-gray-300 transform -rotate-45\'>FinalCut</span></div>'">
                        @else
                            <div class="w-full h-full poster-placeholder flex items-center justify-center bg-gray-100 group-hover:scale-105 transition-transform duration-300">
                                <span class="font-display font-bold text-2xl text-gray-300 transform -rotate-45">FinalCut</span>
                            </div>
                        @endif

                        <!-- Rating Badge (Umur) -->
                        <div class="absolute top-2 right-2">
                            <span class="badge-yellow shadow-hard-sm">{{ $movie->rating_umur }}</span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div>
                        <h2 class="font-display font-bold text-lg text-ink line-clamp-1 group-hover:text-blue-text transition-colors">
                            {{ $movie->title }}
                        </h2>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach ($movie->genres->take(2) as $genre)
                                <span class="badge-blue text-[10px]">{{ $genre->name }}</span>
                            @endforeach
                            @if ($movie->genres->count() > 2)
                                <span class="badge-ink text-[10px]">+{{ $movie->genres->count() - 2 }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full card p-12 text-center">
                    <p class="text-ink-secondary text-lg">Film tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($movies->hasPages())
            <div class="mt-8">
                {{ $movies->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
