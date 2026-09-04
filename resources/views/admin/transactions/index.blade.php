<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Kelola Transaksi</h1>
        </div>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Kelola Transaksi'],
        ]" />

        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <form method="GET" class="card p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Cari User/Film</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input py-1.5 text-sm" placeholder="Nama, email, judul film...">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Status</label>
                    <select name="status" class="input py-1.5 text-sm">
                        <option value="all">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Bioskop</label>
                    <select name="cinema" class="input py-1.5 text-sm">
                        <option value="">Semua Bioskop</option>
                        @foreach ($cinemas as $c)
                            <option value="{{ $c->id }}" {{ request('cinema') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input py-1.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input py-1.5 text-sm">
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if (request()->hasAny(['search', 'status', 'cinema', 'date_from', 'date_to']))
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline btn-sm">Reset</a>
                @endif
            </div>
        </form>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-1.5 border-ink bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">ID</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">User</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Film</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Bioskop</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Tanggal & Jam</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Kursi</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Total</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Status</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3 font-mono text-ink-secondary">#{{ $booking->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-ink">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-ink-secondary">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-ink">{{ $booking->showtime->movie->title }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-ink">{{ $booking->showtime->studio->cinema->name }}</div>
                                    <div class="text-xs text-ink-secondary">{{ $booking->showtime->studio->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-ink">{{ $booking->showtime->show_date->format('d M Y') }}</div>
                                    <div class="text-xs text-ink-secondary">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php $seatLabels = $booking->seats->pluck('seat_number')->map(fn($num, $key) => $num . '(' . strtoupper($booking->seats[$key]->seat_type) . ')')->implode(', '); @endphp
                                    <span class="badge-blue">{{ $seatLabels ?: '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-ink">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($booking->status === 'paid')
                                        <span class="badge-green">Paid</span>
                                    @elseif ($booking->status === 'cancelled')
                                        <span class="badge-red">Cancelled</span>
                                    @else
                                        <span class="badge-yellow">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.transactions.show', $booking) }}" class="btn btn-outline btn-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-ink-secondary">
                                    Tidak ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($bookings->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
