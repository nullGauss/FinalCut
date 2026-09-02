<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Riwayat Transaksi</h1>
    </x-slot>

    <div class="section">
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($bookings as $booking)
            <div class="card p-4 sm:p-6 mb-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Poster -->
                    <div class="w-16 h-22 bg-background border border-gray-300 rounded overflow-hidden shrink-0 flex items-center justify-center">
                        @if ($booking->showtime->movie->poster)
                            <img src="{{ $booking->showtime->movie->poster }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-[9px] text-ink-secondary">No Poster</span>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div>
                                <h3 class="font-display font-bold text-lg text-ink leading-tight">{{ $booking->showtime->movie->title }}</h3>
                                <p class="text-sm text-ink-secondary">{{ $booking->showtime->studio->cinema->name }} &middot; {{ $booking->showtime->studio->name }}</p>
                            </div>
                            @if ($booking->status === 'paid')
                                <span class="badge-green shrink-0">Paid</span>
                            @elseif ($booking->status === 'cancelled')
                                <span class="badge-red shrink-0">Cancelled</span>
                            @else
                                <span class="badge-yellow shrink-0">Pending</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-ink-secondary mb-3">
                            <span>{{ $booking->showtime->show_date->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('H:i') }} WIB</span>
                            <span>Kursi: {{ $booking->seats->pluck('seat_number')->implode(', ') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="font-display font-bold text-ink">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            <div class="flex gap-2">
                                @if ($booking->status === 'pending')
                                    <a href="{{ route('bookings.checkout', $booking) }}" class="btn btn-primary btn-sm">Bayar</a>
                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white"
                                                onclick="return confirm('Batalkan booking ini?')">
                                            Batal
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline btn-sm">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center">
                <p class="text-ink-secondary text-lg mb-2">Belum ada riwayat transaksi</p>
                <p class="text-ink-secondary text-sm mb-4">Mulai pesan tiket film favoritmu!</p>
                <a href="{{ route('movies.index') }}" class="btn btn-primary btn-sm">Browse Film</a>
            </div>
        @endforelse

        @if ($bookings->hasPages())
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
