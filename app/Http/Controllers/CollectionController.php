<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Watchlist;
use App\Models\WatchedDiary;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tab = $request->get('tab', 'watchlist');

        $watchlist = Watchlist::with('movie.genres')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        $diary = WatchedDiary::with('movie.genres')
            ->where('user_id', $user->id)
            ->latest('watched_date')
            ->get();

        return view('collection.index', compact('watchlist', 'diary', 'tab'));
    }

    public function toggleWatchlist(Movie $movie)
    {
        $user = auth()->user();
        $exists = Watchlist::where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->first();

        if ($exists) {
            $exists->delete();
            return back()->with('success', 'Dihapus dari Watchlist.');
        }

        Watchlist::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);

        return back()->with('success', 'Ditambahkan ke Watchlist!');
    }

    public function removeWatchlist(Movie $movie)
    {
        Watchlist::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->delete();

        return back()->with('success', 'Dihapus dari Watchlist.');
    }

    public function addDiary(Request $request, Movie $movie)
    {
        $request->validate([
            'watched_date' => 'required|date|before_or_equal:today',
        ]);

        $user = auth()->user();
        $exists = WatchedDiary::where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->first();

        if ($exists) {
            $exists->update(['watched_date' => $request->watched_date]);
            return back()->with('success', 'Tanggal nonton diperbarui!');
        }

        WatchedDiary::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'watched_date' => $request->watched_date,
        ]);

        return back()->with('success', 'Film ditandai sudah ditonton!');
    }

    public function removeDiary(Movie $movie)
    {
        WatchedDiary::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->delete();

        return back()->with('success', 'Dihapus dari Diary.');
    }
}
