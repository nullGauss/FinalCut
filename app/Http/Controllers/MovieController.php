<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Watchlist;
use App\Models\WatchedDiary;
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

        $nowShowingMovieIds = \App\Models\Showtime::where('show_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->pluck('movie_id')
            ->unique()
            ->toArray();

        return view('movies.index', compact('movies', 'genres', 'nowShowingMovieIds'));
    }

    public function nowShowing(Request $request)
    {
        $query = Movie::with('genres')
            ->whereHas('showtimes', function ($q) {
                $q->where('show_date', '>=', now()->toDateString())
                  ->where('is_active', true);
            })
            ->withCount(['showtimes as active_showtimes_count' => function ($q) {
                $q->where('show_date', '>=', now()->toDateString())
                  ->where('is_active', true);
            }]);

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

        $movies = $query->orderBy('active_showtimes_count', 'desc')
                        ->latest()
                        ->paginate(12)
                        ->withQueryString();
        $genres = Genre::all();

        return view('movies.now-showing', compact('movies', 'genres'));
    }

    public function show(Movie $movie)
    {
        $movie->load('genres');

        $avgRating = $movie->reviews()->avg('rating');
        $reviewCount = $movie->reviews()->count();

        $hasShowtimes = $movie->showtimes()
            ->where('show_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->exists();

        $isWatchlisted = Watchlist::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->exists();

        $isDiaried = WatchedDiary::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->exists();

        return view('movies.show', compact('movie', 'avgRating', 'reviewCount', 'hasShowtimes', 'isWatchlisted', 'isDiaried'));
    }
}
