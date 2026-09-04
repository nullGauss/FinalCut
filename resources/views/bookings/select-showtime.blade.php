<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Pesan Tiket</h1>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Film', 'url' => route('movies.index')],
            ['label' => $movie->title, 'url' => route('movies.show', $movie)],
            ['label' => 'Pilih Jadwal'],
        ]" />
        <!-- Info Film -->
        <div class="card p-6 mb-8">
            <div class="flex gap-6">
                <div class="w-20 h-28 bg-background border border-gray-300 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                    @if ($movie->poster)
                        <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs text-ink-secondary text-center px-1">No Poster</span>
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="font-display font-bold text-xl text-ink mb-1">{{ $movie->title }}</h2>
                    <p class="text-sm text-ink-secondary mb-2">{{ $movie->durasi }} menit &middot; {{ $movie->rating_umur }}</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach ($movie->genres as $genre)
                            <span class="badge-blue text-[10px]">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Pilih Jadwal -->
        <h3 class="font-display font-bold text-lg text-ink mb-4">Pilih Bioskop & Jadwal</h3>

        @if ($showtimes->isEmpty())
            <div class="card p-12 text-center">
                <p class="text-ink-secondary text-lg mb-2">Tidak ada jadwal tayang tersedia</p>
                <p class="text-ink-secondary text-sm">Film ini belum memiliki jadwal tayang di bioskop manapun.</p>
                <a href="{{ route('movies.show', $movie) }}" class="btn btn-primary btn-sm mt-4">Kembali ke Detail Film</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($showtimes as $cinemaKey => $cinemaShowtimes)
                    @php
                        $cinemaName = explode('|', $cinemaKey)[0];
                        $cinemaCity = explode('|', $cinemaKey)[1];
                    @endphp
                    <div class="card overflow-hidden">
                        <!-- Header Bioskop -->
                        <div class="px-6 py-4 border-b-1.5 border-ink bg-background">
                            <h4 class="font-display font-bold text-ink">{{ $cinemaName }}</h4>
                            <p class="text-sm text-ink-secondary">{{ $cinemaCity }}</p>
                        </div>

                        <!-- Grup per Tanggal -->
                        @php
                            $byDate = $cinemaShowtimes->groupBy(fn($st) => $st->show_date->format('Y-m-d'));
                        @endphp

                        @foreach ($byDate as $date => $dateShowtimes)
                            <div class="px-6 py-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div class="text-sm font-bold text-ink-secondary mb-3">
                                    {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($dateShowtimes as $st)
                                        <a href="{{ route('bookings.selectSeats', $st) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 border-1.5 border-ink rounded-full text-sm font-medium text-ink hover:bg-ink hover:text-surface transition-all">
                                            <span>{{ \Carbon\Carbon::parse($st->show_time)->format('H:i') }}</span>
                                            <span class="text-xs text-ink-secondary">WIB</span>
                                            <span class="text-xs font-bold ml-1">Rp {{ number_format($st->price, 0, ',', '.') }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
