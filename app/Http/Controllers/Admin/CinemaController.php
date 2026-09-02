<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Seat;
use App\Models\Studio;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cinema::withCount('studios');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }

        $cinemas = $query->latest('id')->paginate(10)->withQueryString();

        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
        ]);

        Cinema::create($validated);

        return redirect()->route('admin.cinemas.index')->with('success', 'Bioskop berhasil ditambahkan.');
    }

    public function edit(Cinema $cinema)
    {
        return response()->json($cinema);
    }

    public function update(Request $request, Cinema $cinema)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
        ]);

        $cinema->update($validated);

        return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil diperbarui.');
    }

    public function destroy(Cinema $cinema)
    {
        $cinema->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Bioskop berhasil dihapus.');
    }

    // -- STUDIO MANAGEMENT -- //

    public function showStudios(Cinema $cinema)
    {
        $cinema->load('studios.seats');
        return view('admin.cinemas.studios', compact('cinema'));
    }

    public function storeStudio(Request $request, Cinema $cinema)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'rows' => 'required|integer|min:1|max:26', // A to Z
            'cols' => 'required|integer|min:1|max:50',
        ]);

        $capacity = $request->rows * $request->cols;

        $studio = Studio::create([
            'cinema_id' => $cinema->id,
            'name' => $request->name,
            'capacity' => $capacity,
        ]);

        // Generate seats automatically (e.g. Rows: A, B. Cols: 1, 2)
        $seatsData = [];
        for ($i = 0; $i < $request->rows; $i++) {
            $rowLetter = chr(65 + $i); // 65 is ASCII for 'A'
            for ($j = 1; $j <= $request->cols; $j++) {
                // Example logic: first row is VIP, rest is Reguler (bisa diubah nanti jika perlu)
                $type = ($i === 0) ? 'vip' : 'reguler'; 
                
                $seatsData[] = [
                    'studio_id' => $studio->id,
                    'seat_number' => $rowLetter . $j,
                    'seat_type' => $type,
                ];
            }
        }
        Seat::insert($seatsData);

        return back()->with('success', "Studio '{$studio->name}' beserta {$capacity} kursi berhasil digenerate.");
    }

    public function destroyStudio(Cinema $cinema, Studio $studio)
    {
        // Pastikan studio milik bioskop yang benar
        if ($studio->cinema_id !== $cinema->id) {
            abort(404);
        }

        $studio->delete();
        return back()->with('success', 'Studio berhasil dihapus.');
    }
}
