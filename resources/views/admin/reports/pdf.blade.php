<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: sans-serif; font-size: 11px; color: #111; }
        body { padding: 20px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .subtitle { color: #666; margin-bottom: 20px; }
        h2 { font-size: 14px; margin: 20px 0 8px; border-bottom: 2px solid #111; padding-bottom: 4px; }
        .summary { display: flex; gap: 30px; margin-bottom: 20px; }
        .summary-item { }
        .summary-item .label { font-size: 10px; color: #666; text-transform: uppercase; }
        .summary-item .value { font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Pendapatan FinalCut</h1>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Booking</div>
            <div class="value">{{ $totalBookings }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Tiket Terjual</div>
            <div class="value">{{ $totalTickets }}</div>
        </div>
    </div>

    <h2>Rekapitulasi per Film</h2>
    <table>
        <thead>
            <tr>
                <th>Film</th>
                <th class="text-center">Booking</th>
                <th class="text-center">Tiket</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($revenueByMovie as $title => $data)
                <tr>
                    <td>{{ $title }}</td>
                    <td class="text-center">{{ $data['count'] }}</td>
                    <td class="text-center">{{ $data['tickets'] }}</td>
                    <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Rekapitulasi per Bioskop</h2>
    <table>
        <thead>
            <tr>
                <th>Bioskop</th>
                <th class="text-center">Booking</th>
                <th class="text-center">Tiket</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($revenueByCinema as $name => $data)
                <tr>
                    <td>{{ $name }}</td>
                    <td class="text-center">{{ $data['count'] }}</td>
                    <td class="text-center">{{ $data['tickets'] }}</td>
                    <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>User</th>
                <th>Film</th>
                <th>Bioskop</th>
                <th>Kursi</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_date ? $booking->booking_date->format('d M Y H:i') : '-' }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->showtime->movie->title }}</td>
                    <td>{{ $booking->showtime->studio->cinema->name }}</td>
                    <td>{{ $booking->seats->pluck('seat_number')->implode(', ') }}</td>
                    <td class="text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 20px; font-size: 10px; color: #999;">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }} | FinalCut Cinema Platform
    </p>
</body>
</html>
