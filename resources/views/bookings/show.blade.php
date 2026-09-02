<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('bookings.history') }}" class="btn btn-outline btn-sm">&larr; Riwayat</a>
            <h1 class="font-display font-bold text-2xl text-ink">Detail Booking #{{ $booking->id }}</h1>
        </div>
    </x-slot>

    <div class="section">
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="card-sm p-4 mb-6 bg-red-50 border-red-600 text-red-800 text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-lg mx-auto">
            <div class="card p-8 text-center mb-6">
                <!-- Status Icon -->
                @if ($booking->status === 'paid')
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="font-display font-bold text-2xl text-ink mb-1">Terkonfirmasi</h2>
                    <p class="text-ink-secondary mb-6">Tiket kamu sudah terbayar. Sampai jumpa di bioskop!</p>
                @elseif ($booking->status === 'cancelled')
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="font-display font-bold text-2xl text-ink mb-1">Dibatalkan</h2>
                    <p class="text-ink-secondary mb-6">Booking ini sudah dibatalkan.</p>
                @else
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="font-display font-bold text-2xl text-ink mb-1">Menunggu Pembayaran</h2>
                    <p class="text-ink-secondary mb-6">Silakan lakukan pembayaran untuk mengkonfirmasi tiket.</p>
                @endif

                <!-- Detail Tiket -->
                <div class="text-left bg-background border border-gray-200 rounded-lg p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">ID Booking</span>
                        <span class="font-bold text-ink">#{{ $booking->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">Status</span>
                        @if ($booking->status === 'paid')
                            <span class="badge-green">Paid</span>
                        @elseif ($booking->status === 'cancelled')
                            <span class="badge-red">Cancelled</span>
                        @else
                            <span class="badge-yellow">Pending</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">Film</span>
                        <span class="font-medium text-ink">{{ $booking->showtime->movie->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">Bioskop</span>
                        <span class="text-ink">{{ $booking->showtime->studio->cinema->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">Studio</span>
                        <span class="text-ink">{{ $booking->showtime->studio->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">Tanggal & Jam</span>
                        <span class="text-ink">{{ $booking->showtime->show_date->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-secondary">Kursi</span>
                        <span class="font-medium text-ink">{{ $booking->seats->pluck('seat_number')->implode(', ') }}</span>
                    </div>
                    @if ($booking->payment)
                        <div class="flex justify-between">
                            <span class="text-ink-secondary">Metode Bayar</span>
                            <span class="text-ink">{{ $booking->payment->method }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-secondary">Tanggal Bayar</span>
                            <span class="text-ink">{{ $booking->payment->payment_date ? $booking->payment->payment_date->format('d M Y H:i') : '-' }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3 flex justify-between">
                        <span class="font-bold text-ink">Total</span>
                        <span class="font-display font-bold text-lg text-ink">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-4">
                @if ($booking->status === 'pending')
                    <a href="{{ route('bookings.checkout', $booking) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                @endif
                @if ($booking->status === 'paid')
                    <a href="{{ route('bookings.print', $booking) }}" target="_blank" class="btn btn-primary btn-sm">
                        Cetak Tiket
                    </a>
                @endif
                <a href="{{ route('bookings.history') }}" class="btn btn-outline btn-sm">Lihat Riwayat</a>
                <a href="{{ route('movies.index') }}" class="btn btn-outline btn-sm">Browse Film</a>
            </div>
        </div>
    </div>
</x-app-layout>
