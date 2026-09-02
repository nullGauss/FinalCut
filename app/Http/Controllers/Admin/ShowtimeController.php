<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'studio.cinema']);

        if ($request->filled('date')) {
            $query->whereDate('show_date', $request->date);
        }
        
        if ($request->filled('cinema')) {
            $query->whereHas('studio', function($q) use ($request) {
                $q->where('cinema_id', $request->cinema);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $showtimes = $query->orderBy('show_date', 'desc')
                           ->orderBy('show_time', 'asc')
                           ->paginate(15)
                           ->withQueryString();

        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::with('studios')->orderBy('name')->get();

        return view('admin.showtimes.index', compact('showtimes', 'movies', 'cinemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date',
            'show_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = true;

        Showtime::create($validated);

        return redirect()->route('admin.showtimes.index')->with('success', 'Jadwal tayang berhasil ditambahkan.');
    }

    public function edit(Showtime $showtime)
    {
        $showtime->load('studio.cinema');
        return response()->json($showtime);
    }

    public function update(Request $request, Showtime $showtime)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date',
            'show_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['show_time'] = \Carbon\Carbon::parse($validated['show_time'])->format('H:i');

        $showtime->update($validated);

        return redirect()->route('admin.showtimes.index')->with('success', 'Jadwal tayang berhasil diperbarui.');
    }

    public function toggleActive(Showtime $showtime)
    {
        $showtime->update(['is_active' => !$showtime->is_active]);

        $status = $showtime->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.showtimes.index')->with('success', "Jadwal tayang {$status}.");
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('success', 'Jadwal tayang berhasil dihapus.');
    }
}
