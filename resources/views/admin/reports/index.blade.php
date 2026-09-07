<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Laporan & Rekapitulasi</h1>
            <a href="{{ route('admin.reports.exportPdf', request()->query()) }}" class="btn btn-primary btn-sm">
                Export PDF
            </a>
        </div>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan & Rekapitulasi'],
        ]" />

        <!-- Filter -->
        <form method="GET" class="card p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="input py-1.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="input py-1.5 text-sm">
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
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Film</label>
                    <select name="movie" class="input py-1.5 text-sm">
                        <option value="">Semua Film</option>
                        @foreach ($movies as $m)
                            <option value="{{ $m->id }}" {{ request('movie') == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary btn-sm h-[34px]">Filter</button>
                    <a href="{{ route('admin.reports.index') }}" class="ml-2 text-sm text-blue-text hover:underline mb-2">Reset</a>
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-ink-secondary">Total Pendapatan</span>
                </div>
                <div class="font-display font-bold text-2xl text-ink">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <span class="text-sm font-bold text-ink-secondary">Total Booking</span>
                </div>
                <div class="font-display font-bold text-2xl text-ink">{{ $totalBookings }}</div>
            </div>
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-ink-secondary">Total Tiket Terjual</span>
                </div>
                <div class="font-display font-bold text-2xl text-ink">{{ $totalTickets }}</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue by Date -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Pendapatan Harian</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Revenue by Movie -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Pendapatan per Film</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="movieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue by Cinema -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Pendapatan per Bioskop</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="cinemaChart"></canvas>
                </div>
            </div>

            <!-- Booking Status Pie -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Status Booking</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Rekap per Film -->
        <div class="card overflow-hidden mb-8">
            <div class="px-6 py-4 border-b-1.5 border-ink">
                <h3 class="font-display font-bold text-lg text-ink">Rekapitulasi per Film</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Film</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Total Booking</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Tiket Terjual</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($revenueByMovie as $title => $data)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3 font-medium text-ink">{{ $title }}</td>
                                <td class="px-4 py-3 text-center text-ink-secondary">{{ $data['count'] }}</td>
                                <td class="px-4 py-3 text-center text-ink-secondary">{{ $data['tickets'] }}</td>
                                <td class="px-4 py-3 text-right font-medium text-ink">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-ink-secondary">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Rekap per Bioskop -->
        <div class="card overflow-hidden mb-8">
            <div class="px-6 py-4 border-b-1.5 border-ink">
                <h3 class="font-display font-bold text-lg text-ink">Rekapitulasi per Bioskop</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Bioskop</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Total Booking</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Tiket Terjual</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($revenueByCinema as $name => $data)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3 font-medium text-ink">{{ $name }}</td>
                                <td class="px-4 py-3 text-center text-ink-secondary">{{ $data['count'] }}</td>
                                <td class="px-4 py-3 text-center text-ink-secondary">{{ $data['tickets'] }}</td>
                                <td class="px-4 py-3 text-right font-medium text-ink">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-ink-secondary">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b-1.5 border-ink">
                <h3 class="font-display font-bold text-lg text-ink">Detail Semua Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Tanggal</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">User</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Film</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Bioskop</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Kursi</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3 text-ink-secondary">{{ $booking->booking_date ? $booking->booking_date->format('d M Y H:i') : '-' }}</td>
                                <td class="px-4 py-3 font-medium text-ink">{{ $booking->user->name }}</td>
                                <td class="px-4 py-3 text-ink">{{ $booking->showtime->movie->title }}</td>
                                <td class="px-4 py-3 text-ink-secondary">{{ $booking->showtime->studio->cinema->name }}</td>
                                <td class="px-4 py-3 text-center text-ink-secondary">{{ $booking->seats->pluck('seat_number')->implode(', ') }}</td>
                                <td class="px-4 py-3 text-right font-medium text-ink">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-ink-secondary">Tidak ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const dailyLabels = @json($revenueByDate->keys()->toArray());
        const dailyData = @json($revenueByDate->values()->toArray());
        const movieLabels = @json($revenueByMovie->keys()->take(10)->toArray());
        const movieData = @json($revenueByMovie->values()->take(10)->pluck('revenue')->toArray());
        const cinemaLabels = @json($revenueByCinema->keys()->toArray());
        const cinemaData = @json($revenueByCinema->values()->pluck('revenue')->toArray());
        const statusPaid = @json(\App\Models\Booking::where('status', 'paid')->count());
        const statusPending = @json(\App\Models\Booking::where('status', 'pending')->count());
        const statusCancelled = @json(\App\Models\Booking::where('status', 'cancelled')->count());

        // Daily Revenue Chart
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dailyData,
                    borderColor: '#111111',
                    backgroundColor: '#DCE9F5',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Movie Revenue Chart
        new Chart(document.getElementById('movieChart'), {
            type: 'bar',
            data: {
                labels: movieLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: movieData,
                    backgroundColor: '#DCE9F5',
                    borderColor: '#111111',
                    borderWidth: 1.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Cinema Revenue Chart
        new Chart(document.getElementById('cinemaChart'), {
            type: 'bar',
            data: {
                labels: cinemaLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: cinemaData,
                    backgroundColor: '#FBEBA0',
                    borderColor: '#111111',
                    borderWidth: 1.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Status Pie Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [statusPaid, statusPending, statusCancelled],
                    backgroundColor: ['#22C55E', '#EAB308', '#EF4444'],
                    borderColor: '#111111',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
