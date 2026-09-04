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
        $sort = $request->get('sort', 'newest');

        $watchlist = Watchlist::with('movie.genres')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        $diaryQuery = WatchedDiary::with(['movie.genres', 'movie.reviews' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->where('watched_diary.user_id', $user->id);

        switch ($sort) {
            case 'oldest':
                $diaryQuery->oldest('watched_date');
                break;
            case 'rating_high':
            case 'rating_low':
                // Load semua diary dulu, sort di collection Laravel
                $diary = $diaryQuery->get()->sortBy(function ($item) use ($sort) {
                    $review = $item->movie->reviews->first();
                    $rating = $review ? $review->rating : 0;
                    return $sort === 'rating_high' ? -$rating : $rating;
                }, SORT_REGULAR, false)->values();
                return view('collection.index', compact('watchlist', 'diary', 'tab', 'sort'));
            case 'az':
                $diary = $diaryQuery->get()->sortBy(function ($item) {
                    return $item->movie->title;
                }, SORT_STRING, false)->values();
                return view('collection.index', compact('watchlist', 'diary', 'tab', 'sort'));
            default: // newest
                $diaryQuery->latest('watched_date');
                break;
        }

        $diary = $diaryQuery->get();

        return view('collection.index', compact('watchlist', 'diary', 'tab', 'sort'));
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
