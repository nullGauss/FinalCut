<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cinema;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with([
            'user',
            'showtime.movie',
            'showtime.studio.cinema',
            'seats',
            'payment',
        ]);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        if ($request->filled('cinema')) {
            $query->whereHas('showtime.studio', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('showtime.movie', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.transactions.index', compact('bookings', 'cinemas'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'showtime.movie',
            'showtime.studio.cinema',
            'seats',
            'payment',
        ]);

        return view('admin.transactions.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $validated['status']]);

        if ($validated['status'] === 'paid' && $oldStatus !== 'paid') {
            $booking->payment()->updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->total_price,
                    'method' => $request->input('payment_method', 'Tunai'),
                    'status' => 'success',
                    'payment_date' => now(),
                ]
            );
        }

        if ($validated['status'] === 'cancelled' && $oldStatus !== 'cancelled') {
            $payment = $booking->payment;
            if ($payment && $payment->status === 'success') {
                $payment->update(['status' => 'failed']);
            }
        }

        return redirect()->route('admin.transactions.show', $booking)
                         ->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
