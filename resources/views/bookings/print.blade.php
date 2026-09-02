<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket FinalCut #{{ $booking->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fff; color: #111; }

        .ticket {
            max-width: 600px;
            margin: 20px auto;
            border: 2px solid #111;
            border-radius: 12px;
            overflow: hidden;
        }

        .ticket-header {
            background: #111;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .ticket-header h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .ticket-header h1 span { color: #DCE9F5; }

        .ticket-body { padding: 20px; }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
            font-size: 14px;
        }

        .ticket-row:last-child { border-bottom: none; }

        .ticket-label { color: #666; }
        .ticket-value { font-weight: 600; text-align: right; }

        .ticket-total {
            background: #F7F7F7;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px solid #111;
        }

        .ticket-total .label { font-size: 14px; font-weight: 600; }
        .ticket-total .value { font-size: 24px; font-weight: 800; }

        .ticket-footer {
            padding: 16px 20px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px dashed #ccc;
        }

        .ticket-footer .barcode {
            font-family: monospace;
            font-size: 18px;
            letter-spacing: 4px;
            margin-bottom: 8px;
            color: #111;
        }

        .btn-print {
            display: block;
            max-width: 600px;
            margin: 20px auto;
            padding: 12px 24px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }

        .btn-print:hover { background: #333; }

        @media print {
            body { background: #fff; }
            .btn-print { display: none !important; }
            .ticket { margin: 0; border: 2px solid #000; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>Final<span>Cut</span></h1>
        </div>

        <div class="ticket-body">
            <div class="ticket-row">
                <span class="ticket-label">ID Booking</span>
                <span class="ticket-value">#{{ $booking->id }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Film</span>
                <span class="ticket-value">{{ $booking->showtime->movie->title }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Bioskop</span>
                <span class="ticket-value">{{ $booking->showtime->studio->cinema->name }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Studio</span>
                <span class="ticket-value">{{ $booking->showtime->studio->name }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Tanggal</span>
                <span class="ticket-value">{{ $booking->showtime->show_date->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Jam</span>
                <span class="ticket-value">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('H:i') }} WIB</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Kursi</span>
                <span class="ticket-value">{{ $booking->seats->pluck('seat_number')->implode(', ') }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Metode Bayar</span>
                <span class="ticket-value">{{ $booking->payment->method ?? '-' }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Status</span>
                <span class="ticket-value" style="color: {{ $booking->status === 'paid' ? '#16a34a' : ($booking->status === 'cancelled' ? '#dc2626' : '#ca8a04') }}">
                    {{ strtoupper($booking->status) }}
                </span>
            </div>
        </div>

        <div class="ticket-total">
            <span class="label">TOTAL BAYAR</span>
            <span class="value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="ticket-footer">
            <div class="barcode">FC-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
            <p>Simpan tiket ini sebagai bukti pembayaran yang sah.</p>
            <p>Dicetak: {{ now()->format('d M Y H:i') }} | FinalCut Cinema Platform</p>
        </div>
    </div>

    <button class="btn-print" onclick="window.print()">Cetak Tiket</button>
</body>
</html>
