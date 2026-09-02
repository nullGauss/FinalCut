<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // Cek apakah user sudah mereview film ini (sesuai C_BusinessLogic.md 1 user 1 review per film)
        $existingReview = Review::where('user_id', auth()->id())
            ->where('movie_id', $movie->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Kamu sudah mengulas film ini. Hapus ulasan lama jika ingin mengubahnya.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'movie_id' => $movie->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        // Pastikan hanya pemilik yang bisa hapus
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
