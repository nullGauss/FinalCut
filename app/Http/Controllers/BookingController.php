<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function selectShowtime(Movie $movie)
    {
        $movie->load('genres');

        $showtimes = Showtime::with(['studio.cinema'])
            ->where('movie_id', $movie->id)
            ->where('show_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->orderBy('show_date', 'asc')
            ->orderBy('show_time', 'asc')
            ->get()
            ->groupBy(function ($st) {
                return $st->studio->cinema->name . '|' . $st->studio->cinema->city;
            });

        return view('bookings.select-showtime', compact('movie', 'showtimes'));
    }

    public function selectSeats(Showtime $showtime)
    {
        $showtime->load(['movie', 'studio.cinema', 'studio.seats']);

        if (!$showtime->is_active) {
            return redirect()->route('bookings.selectShowtime', $showtime->movie)
                             ->with('error', 'Jadwal tayang ini sudah tidak tersedia.');
        }

        $studio = $showtime->studio;
        $allSeats = $studio->seats->sortBy('seat_number');

        $bookedSeatIds = Booking::where('showtime_id', $showtime->id)
            ->where('status', '!=', 'cancelled')
            ->with('seats')
            ->get()
            ->pluck('seats')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->toArray();

        $seatsByRow = $allSeats->groupBy(function ($seat) {
            return substr($seat->seat_number, 0, 1);
        });

        return view('bookings.select-seats', compact('showtime', 'seatsByRow', 'bookedSeatIds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $showtime = Showtime::findOrFail($validated['showtime_id']);

        if (!$showtime->is_active) {
            return back()->withErrors(['showtime_id' => 'Jadwal tayang ini sudah tidak tersedia.']);
        }

        $seatIds = $validated['seat_ids'];

        if (empty($seatIds)) {
            return back()->withErrors(['seat_ids' => 'Pilih minimal satu kursi.']);
        }

        $seats = Seat::whereIn('id', $seatIds)
                     ->where('studio_id', $showtime->studio_id)
                     ->get();

        if ($seats->count() !== count($seatIds)) {
            return back()->withErrors(['seat_ids' => 'Ada kursi yang tidak valid.']);
        }

        $totalPrice = 0;
        foreach ($seats as $seat) {
            $seatPrice = $seat->seat_type === 'vip'
                ? $showtime->price * 1.25
                : $showtime->price;
            $totalPrice += $seatPrice;
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'showtime_id' => $showtime->id,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_date' => now(),
        ]);

        $booking->seats()->sync($seatIds);

        return redirect()->route('bookings.checkout', $booking)
                         ->with('success', 'Booking dibuat! Silakan lakukan pembayaran.');
    }

    public function checkout(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.studio.cinema', 'seats', 'payment']);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking);
        }

        return view('bookings.checkout', compact('booking'));
    }

    public function pay(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                             ->with('error', 'Booking ini sudah diproses.');
        }

        $validated = $request->validate([
            'method' => 'required|in:Tunai,Kartu Kredit,Transfer Bank,E-Wallet',
        ]);

        $booking->update(['status' => 'paid']);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'method' => $validated['method'],
            'status' => 'success',
            'payment_date' => now(),
        ]);

        return redirect()->route('bookings.show', $booking)
                         ->with('success', 'Pembayaran berhasil! Tiket kamu sudah terkonfirmasi.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                             ->with('error', 'Hanya booking pending yang bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.history')
                         ->with('success', 'Booking berhasil dibatalkan. Kursi sudah tersedia kembali.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.studio.cinema', 'seats', 'payment']);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function history()
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.studio.cinema', 'seats', 'payment'])
            ->where('user_id', auth()->id())
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function print(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.studio.cinema', 'seats', 'payment']);

        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('bookings.print', compact('booking'));
    }
}
