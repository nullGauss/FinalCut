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
                                    <button type="button" onclick="openCancelBookingModal({{ $booking->id }})" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                                        Batal
                                    </button>
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

    <!-- Modal Batalkan Booking -->
    <div id="cancelBookingModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeCancelBookingModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Batalkan Booking?</h3>
                <p class="text-ink-secondary text-sm mb-6">Kursi yang dipilih akan tersedia kembali untuk dipesan orang lain.</p>
                <div class="flex gap-3">
                    <button onclick="closeCancelBookingModal()" class="btn btn-outline btn-sm flex-1">Kembali</button>
                    <form id="cancelBookingForm" method="POST" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm w-full justify-center border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCancelBookingModal(id) {
            const form = document.getElementById('cancelBookingForm');
            form.action = `/bookings/${id}/cancel`;
            document.getElementById('cancelBookingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCancelBookingModal() {
            document.getElementById('cancelBookingModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
