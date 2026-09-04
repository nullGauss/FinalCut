<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use App\Models\WatchedDiary;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $existingReview = Review::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->first();

        if ($existingReview) {
            // Sudah ada review → update
            $existingReview->update([
                'rating' => $request->rating,
                'review_text' => $request->review_text,
            ]);

            return back()->with('success', 'Ulasan berhasil diperbarui!');
        }

        // Belum ada review → create baru
        Review::create([
            'user_id' => auth()->id(),
            'movie_id' => $movie->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        // Auto-masuk diary kalau belum ada entry untuk film ini
        $hasDiary = WatchedDiary::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->exists();

        if (!$hasDiary) {
            WatchedDiary::create([
                'user_id' => auth()->id(),
                'movie_id' => $movie->id,
                'watched_date' => now()->toDateString(),
            ]);
        }

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
