<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Dashboard Admin</h1>
            <span class="badge-ink">Admin</span>
        </div>
    </x-slot>

    <div class="section">
        <!-- Welcome Banner -->
        <div class="card p-8 mb-8">
            <p class="handwriting text-xl text-ink-secondary mb-2">Panel administrator~</p>
            <h2 class="font-display font-bold text-3xl text-ink mb-2">
                Halo, {{ Auth::user()->name }}!
            </h2>
            <p class="text-ink-secondary">
                Kelola data film, jadwal tayang, bioskop, dan pantau transaksi dari satu tempat.
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="card p-6 text-center">
                <div class="font-display font-bold text-3xl text-ink">{{ $stats['total_users'] }}</div>
                <div class="text-sm text-ink-secondary">Total User</div>
            </div>
            <div class="card p-6 text-center">
                <div class="font-display font-bold text-3xl text-ink">{{ $stats['total_movies'] }}</div>
                <div class="text-sm text-ink-secondary">Total Film</div>
            </div>
            <div class="card p-6 text-center">
                <div class="font-display font-bold text-3xl text-ink">{{ $stats['total_bookings'] }}</div>
                <div class="text-sm text-ink-secondary">Total Booking</div>
            </div>
            <div class="card p-6 text-center">
                <div class="font-display font-bold text-3xl text-ink">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                <div class="text-sm text-ink-secondary">Total Pendapatan</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h3 class="font-display font-bold text-lg text-ink mb-4">Kelola Data</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.movies.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Kelola Film</h4>
                <p class="text-sm text-ink-secondary">Tambah, edit, hapus data film</p>
            </a>

            <a href="{{ route('admin.cinemas.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Kelola Bioskop</h4>
                <p class="text-sm text-ink-secondary">Kelola bioskop & studio</p>
            </a>

            <a href="{{ route('admin.showtimes.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Jadwal Tayang</h4>
                <p class="text-sm text-ink-secondary">Atur jadwal tayang film</p>
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Transaksi</h4>
                <p class="text-sm text-ink-secondary">Pantau semua booking user</p>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Laporan</h4>
                <p class="text-sm text-ink-secondary">Grafik pendapatan & export PDF</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Kelola User</h4>
                <p class="text-sm text-ink-secondary">Ubah role & status akun</p>
            </a>
        </div>

        <!-- Top Movies & Pending Bookings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top 5 Film Terlaris -->
            @if ($stats['top_movies']->count() > 0)
                <div class="card">
                    <div class="p-4 border-b-1.5 border-ink">
                        <h3 class="font-display font-bold text-ink">Film Terlaris</h3>
                    </div>
                    <div class="p-4">
                        @foreach ($stats['top_movies'] as $index => $movie)
                            <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-200' : '' }}">
                                <span class="font-display font-bold text-ink text-lg w-6">{{ $index + 1 }}</span>
                                @if ($movie['poster'])
                                    <img src="{{ $movie['poster'] }}" alt="{{ $movie['title'] }}" class="w-10 h-14 object-cover border border-ink" onerror="this.outerHTML='<div class=\'w-10 h-14 bg-gray-100 border border-ink flex items-center justify-center text-xs text-gray-400\'>?</div>'">
                                @else
                                    <div class="w-10 h-14 bg-gray-100 border border-ink flex items-center justify-center text-xs text-ink-secondary">No<br>Poster</div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-ink truncate">{{ $movie['title'] }}</div>
                                    <div class="text-xs text-ink-secondary">{{ $movie['total_bookings'] }} booking · Rp {{ number_format($movie['total_revenue'], 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card p-8 text-center">
                    <p class="text-ink-secondary">Belum ada data booking.</p>
                </div>
            @endif

            <!-- Status Summary -->
            <div class="card">
                <div class="p-4 border-b-1.5 border-ink">
                    <h3 class="font-display font-bold text-ink">Status Booking</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                        <span class="font-medium text-green-700">Lunas (Paid)</span>
                        <span class="font-display font-bold text-green-700">{{ \App\Models\Booking::where('status', 'paid')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <span class="font-medium text-yellow-700">Menunggu (Pending)</span>
                        <span class="font-display font-bold text-yellow-700">{{ $stats['pending_bookings'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                        <span class="font-medium text-red-700">Dibatalkan</span>
                        <span class="font-display font-bold text-red-700">{{ \App\Models\Booking::where('status', 'cancelled')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <span class="font-medium text-blue-700">Jadwal Aktif</span>
                        <span class="font-display font-bold text-blue-700">{{ $stats['active_showtimes'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        @if ($stats['recent_bookings']->count() > 0)
            <h3 class="font-display font-bold text-lg text-ink mb-4">Booking Terbaru</h3>
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-1.5 border-ink bg-background">
                                <th class="text-left px-4 py-3 font-bold text-ink">User</th>
                                <th class="text-left px-4 py-3 font-bold text-ink">Film</th>
                                <th class="text-left px-4 py-3 font-bold text-ink">Bioskop</th>
                                <th class="text-left px-4 py-3 font-bold text-ink">Status</th>
                                <th class="text-right px-4 py-3 font-bold text-ink">Total</th>
                                <th class="text-right px-4 py-3 font-bold text-ink">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stats['recent_bookings'] as $booking)
                                <tr class="{{ !$loop->last ? 'border-b border-gray-200' : '' }} hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-ink">{{ $booking->user->name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-ink-secondary">
                                        {{ $booking->showtime->movie->title }}
                                    </td>
                                    <td class="px-4 py-3 text-ink-secondary text-xs">
                                        {{ $booking->showtime->studio->cinema->name ?? '-' }}<br>
                                        Studio {{ $booking->showtime->studio->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($booking->status === 'paid')
                                            <span class="badge-green">Paid</span>
                                        @elseif ($booking->status === 'cancelled')
                                            <span class="badge-red">Cancelled</span>
                                        @else
                                            <span class="badge-yellow">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-ink">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-xs text-ink-secondary">
                                        {{ $booking->booking_date->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
