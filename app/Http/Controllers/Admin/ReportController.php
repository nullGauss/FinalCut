<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with([
            'user',
            'showtime.movie',
            'showtime.studio.cinema',
            'payment',
        ])->where('status', '!=', 'cancelled');

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        } else {
            $query->whereDate('booking_date', '>=', now()->startOfMonth());
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        } else {
            $query->whereDate('booking_date', '<=', now()->endOfMonth());
        }

        if ($request->filled('cinema')) {
            $query->whereHas('showtime.studio', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema);
            });
        }

        if ($request->filled('movie')) {
            $query->whereHas('showtime', function ($q) use ($request) {
                $q->where('movie_id', $request->movie);
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();
        $totalTickets = $bookings->sum(function ($b) {
            return $b->seats->count();
        });

        $revenueByMovie = $bookings->groupBy('showtime.movie.title')->map(function ($group) {
            return [
                'revenue' => $group->sum('total_price'),
                'count' => $group->count(),
                'tickets' => $group->sum(fn ($b) => $b->seats->count()),
            ];
        })->sortByDesc('revenue')->take(10);

        $revenueByCinema = $bookings->groupBy('showtime.studio.cinema.name')->map(function ($group) {
            return [
                'revenue' => $group->sum('total_price'),
                'count' => $group->count(),
                'tickets' => $group->sum(fn ($b) => $b->seats->count()),
            ];
        })->sortByDesc('revenue');

        $revenueByDate = $bookings->groupBy(fn ($b) => $b->booking_date->format('Y-m-d'))
                                  ->map(fn ($group) => $group->sum('total_price'))
                                  ->sortKeys();

        $cinemas = Cinema::orderBy('name')->get();
        $movies = Movie::orderBy('title')->get();

        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->endOfMonth()->format('Y-m-d'));

        return view('admin.reports.index', compact(
            'bookings', 'totalRevenue', 'totalBookings', 'totalTickets',
            'revenueByMovie', 'revenueByCinema', 'revenueByDate',
            'cinemas', 'movies', 'dateFrom', 'dateTo'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Booking::with([
            'user',
            'showtime.movie',
            'showtime.studio.cinema',
            'payment',
        ])->where('status', '!=', 'cancelled');

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        } else {
            $query->whereDate('booking_date', '>=', now()->startOfMonth());
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        } else {
            $query->whereDate('booking_date', '<=', now()->endOfMonth());
        }

        if ($request->filled('cinema')) {
            $query->whereHas('showtime.studio', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema);
            });
        }

        if ($request->filled('movie')) {
            $query->whereHas('showtime', function ($q) use ($request) {
                $q->where('movie_id', $request->movie);
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();
        $totalTickets = $bookings->sum(fn ($b) => $b->seats->count());

        $revenueByMovie = $bookings->groupBy('showtime.movie.title')->map(function ($group) {
            return [
                'revenue' => $group->sum('total_price'),
                'count' => $group->count(),
                'tickets' => $group->sum(fn ($b) => $b->seats->count()),
            ];
        })->sortByDesc('revenue')->take(10);

        $revenueByCinema = $bookings->groupBy('showtime.studio.cinema.name')->map(function ($group) {
            return [
                'revenue' => $group->sum('total_price'),
                'count' => $group->count(),
                'tickets' => $group->sum(fn ($b) => $b->seats->count()),
            ];
        })->sortByDesc('revenue');

        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->endOfMonth()->format('Y-m-d'));

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'bookings', 'totalRevenue', 'totalBookings', 'totalTickets',
            'revenueByMovie', 'revenueByCinema', 'dateFrom', 'dateTo'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("laporan-finalcut-{$dateFrom}-sd-{$dateTo}.pdf");
    }
}
