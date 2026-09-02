<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
            <h1 class="font-display font-bold text-2xl text-ink">Detail Transaksi #{{ $booking->id }}</h1>
            @if ($booking->status === 'paid')
                <a href="{{ route('bookings.print', $booking) }}" target="_blank" class="btn btn-primary btn-sm ml-auto">
                    Cetak Tiket
                </a>
            @endif
        </div>
    </x-slot>

    <div class="section">
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-[1fr_380px] gap-8 items-start">
            <!-- Info Utama -->
            <div class="space-y-6">
                <!-- Info Booking -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Informasi Booking</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block font-bold text-ink">ID Transaksi</span>
                            <span class="text-ink-secondary">#{{ $booking->id }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Tanggal Booking</span>
                            <span class="text-ink-secondary">{{ $booking->booking_date ? $booking->booking_date->format('d M Y H:i') : '-' }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Status</span>
                            @if ($booking->status === 'paid')
                                <span class="badge-green">Paid</span>
                            @elseif ($booking->status === 'cancelled')
                                <span class="badge-red">Cancelled</span>
                            @else
                                <span class="badge-yellow">Pending</span>
                            @endif
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Total Harga</span>
                            <span class="text-ink-secondary font-bold text-lg">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Film & Jadwal -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Film & Jadwal Tayang</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block font-bold text-ink">Judul Film</span>
                            <span class="text-ink-secondary">{{ $booking->showtime->movie->title }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Rating Umur</span>
                            <span class="text-ink-secondary">{{ $booking->showtime->movie->rating_umur }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Bioskop</span>
                            <span class="text-ink-secondary">{{ $booking->showtime->studio->cinema->name }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Studio</span>
                            <span class="text-ink-secondary">{{ $booking->showtime->studio->name }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Tanggal Tayang</span>
                            <span class="text-ink-secondary">{{ $booking->showtime->show_date->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Jam Tayang</span>
                            <span class="text-ink-secondary">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Info Kursi -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Kursi yang Dipesan</h3>
                    @if ($booking->seats->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach ($booking->seats as $seat)
                                <span class="{{ $seat->seat_type === 'vip' ? 'badge-yellow' : 'badge-blue' }} text-sm">
                                    {{ $seat->seat_number }}
                                    ({{ strtoupper($seat->seat_type) }})
                                </span>
                            @endforeach
                        </div>
                        @php
                            $regulerSeats = $booking->seats->where('seat_type', 'reguler');
                            $vipSeats = $booking->seats->where('seat_type', 'vip');
                            $basePrice = $booking->showtime->price;
                            $vipPrice = $basePrice * 1.25;
                        @endphp
                        <div class="text-xs text-ink-secondary mt-3 space-y-1">
                            @if ($regulerSeats->count() > 0)
                                <div>Reguler: {{ $regulerSeats->count() }} kursi x Rp {{ number_format($basePrice, 0, ',', '.') }}</div>
                            @endif
                            @if ($vipSeats->count() > 0)
                                <div>VIP: {{ $vipSeats->count() }} kursi x Rp {{ number_format($vipPrice, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    @else
                        <p class="text-ink-secondary text-sm">Tidak ada data kursi.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info User -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Data User</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block font-bold text-ink">Nama</span>
                            <span class="text-ink-secondary">{{ $booking->user->name }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Email</span>
                            <span class="text-ink-secondary">{{ $booking->user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Pembayaran -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Status Pembayaran</h3>
                    @if ($booking->payment)
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="block font-bold text-ink">Metode</span>
                                <span class="text-ink-secondary">{{ $booking->payment->method ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="block font-bold text-ink">Status</span>
                                @if ($booking->payment->status === 'success')
                                    <span class="badge-green">Berhasil</span>
                                @else
                                    <span class="badge-red">Gagal</span>
                                @endif
                            </div>
                            <div>
                                <span class="block font-bold text-ink">Tanggal Bayar</span>
                                <span class="text-ink-secondary">{{ $booking->payment->payment_date ? $booking->payment->payment_date->format('d M Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-ink-secondary text-sm">Belum ada pembayaran.</p>
                    @endif
                </div>

                <!-- Update Status -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Ubah Status</h3>
                    <form method="POST" action="{{ route('admin.transactions.updateStatus', $booking) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="label">Status Booking</label>
                                <select name="status" class="input">
                                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $booking->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="label">Metode Pembayaran</label>
                                <select name="payment_method" class="input">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Kartu Kredit">Kartu Kredit</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-full">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
