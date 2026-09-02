<?php

namespace App\Http\Controllers;

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

        $movies = $query->latest()->paginate(12)->withQueryString();
        $genres = Genre::all();

        return view('movies.index', compact('movies', 'genres'));
    }

    public function show(Movie $movie)
    {
        $movie->load('genres');

        $avgRating = $movie->reviews()->avg('rating');
        $reviewCount = $movie->reviews()->count();

        return view('movies.show', compact('movie', 'avgRating', 'reviewCount'));
    }
}
