<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('genres');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('director', 'like', "%{$search}%");
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        $movies = $query->latest()->paginate(10)->withQueryString();
        $genres = Genre::all();

        return view('admin.movies.index', compact('movies', 'genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'sinopsis' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'rating_umur' => 'nullable|string|max:10',
            'poster' => 'nullable|string|max:255',
            'trailer_url' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'director' => 'nullable|string|max:100',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        $genres = $validated['genres'];
        unset($validated['genres']);

        $movie = Movie::create($validated);
        $movie->genres()->sync($genres);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil ditambahkan.');
    }

    public function edit(Movie $movie)
    {
        $movie->load('genres');
        $genres = Genre::all();

        return response()->json([
            'movie' => $movie,
            'genres' => $genres,
            'selected_genres' => $movie->genres->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'sinopsis' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'rating_umur' => 'nullable|string|max:10',
            'poster' => 'nullable|string|max:255',
            'trailer_url' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'director' => 'nullable|string|max:100',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        $genres = $validated['genres'];
        unset($validated['genres']);

        $movie->update($validated);
        $movie->genres()->sync($genres);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil diperbarui.');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dihapus.');
    }
}
