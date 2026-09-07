<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\EmailChangeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $user = auth()->user();
    $stats = [
        'reviews' => $user->reviews()->count(),
        'watchlist' => $user->watchlist()->count(),
        'diary' => $user->watchedDiary()->count(),
        'bookings' => $user->bookings()->count(),
    ];

    // Now showing movies
    $nowShowing = \App\Models\Movie::with('genres')
        ->whereHas('showtimes', function ($q) {
            $q->where('show_date', '>=', now()->toDateString())
              ->where('is_active', true);
        })
        ->withCount(['showtimes as active_showtimes_count' => function ($q) {
            $q->where('show_date', '>=', now()->toDateString())
              ->where('is_active', true);
        }])
        ->orderBy('active_showtimes_count', 'desc')
        ->take(6)
        ->get();

    // Spotlight: top 2 movies by review count (most reviewed)
    $spotlight = \App\Models\Movie::with('genres')
        ->whereHas('reviews')
        ->withCount('reviews')
        ->orderBy('reviews_count', 'desc')
        ->take(2)
        ->get();

    // Popular reviews from other users (latest 5)
    $popularReviews = \App\Models\Review::with(['user', 'movie'])
        ->where('user_id', '!=', $user->id)
        ->latest()
        ->take(5)
        ->get();

    // Recent collection (watchlist + diary, merged & sorted by created_at)
    $recentWatchlist = $user->watchlist()->with('movie')->latest()->take(4)->get()->map(function ($item) {
        return ['type' => 'watchlist', 'movie' => $item->movie, 'date' => $item->created_at];
    });
    $recentDiary = $user->watchedDiary()->with('movie')->latest()->take(4)->get()->map(function ($item) {
        return ['type' => 'diary', 'movie' => $item->movie, 'date' => $item->created_at, 'watched_date' => $item->watched_date];
    });
    $recentCollection = $recentWatchlist->concat($recentDiary)->sortByDesc('date')->take(6)->values();

    // Recent bookings
    $recentBookings = $user->bookings()
        ->with(['showtime.movie', 'showtime.studio.cinema'])
        ->latest('booking_date')
        ->take(3)
        ->get();

    return view('dashboard', compact('stats', 'nowShowing', 'recentBookings', 'spotlight', 'popularReviews', 'recentCollection'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_movies' => \App\Models\Movie::count(),
            'total_bookings' => \App\Models\Booking::count(),
            'total_revenue' => \App\Models\Booking::where('status', 'paid')->sum('total_price'),
            'recent_bookings' => \App\Models\Booking::with(['user', 'showtime.movie', 'showtime.studio.cinema'])
                ->latest('booking_date')
                ->take(5)
                ->get(),
            'top_movies' => \App\Models\Booking::where('status', '!=', 'cancelled')
                ->with('showtime.movie')
                ->get()
                ->groupBy('showtime.movie.title')
                ->map(function ($group) {
                    $movie = $group->first()->showtime->movie;
                    return [
                        'id' => $movie->id,
                        'title' => $movie->title,
                        'poster' => $movie->poster,
                        'total_bookings' => $group->count(),
                        'total_revenue' => $group->sum('total_price'),
                    ];
                })
                ->sortByDesc('total_bookings')
                ->take(5)
                ->values(),
            'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
            'active_showtimes' => \App\Models\Showtime::where('show_date', '>=', now()->toDateString())
                ->where('is_active', true)
                ->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    Route::get('/movies', [AdminMovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [AdminMovieController::class, 'show'])->name('movies.show');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{movie}/edit', [AdminMovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{movie}', [AdminMovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{movie}', [AdminMovieController::class, 'destroy'])->name('movies.destroy');
    Route::delete('/movies/{movie}/reviews/{review}', [AdminMovieController::class, 'destroyReview'])->name('movies.reviews.destroy');

    // Kelola Bioskop & Studio
    Route::get('/cinemas', [CinemaController::class, 'index'])->name('cinemas.index');
    Route::post('/cinemas', [CinemaController::class, 'store'])->name('cinemas.store');
    Route::get('/cinemas/{cinema}/edit', [CinemaController::class, 'edit'])->name('cinemas.edit');
    Route::put('/cinemas/{cinema}', [CinemaController::class, 'update'])->name('cinemas.update');
    Route::delete('/cinemas/{cinema}', [CinemaController::class, 'destroy'])->name('cinemas.destroy');
    Route::get('/cinemas/{cinema}/studios', [CinemaController::class, 'showStudios'])->name('cinemas.studios');
    Route::post('/cinemas/{cinema}/studios', [CinemaController::class, 'storeStudio'])->name('cinemas.studios.store');
    Route::delete('/cinemas/{cinema}/studios/{studio}', [CinemaController::class, 'destroyStudio'])->name('cinemas.studios.destroy');

    // Kelola Jadwal Tayang
    Route::get('/showtimes', [ShowtimeController::class, 'index'])->name('showtimes.index');
    Route::post('/showtimes', [ShowtimeController::class, 'store'])->name('showtimes.store');
    Route::get('/showtimes/{showtime}/edit', [ShowtimeController::class, 'edit'])->name('showtimes.edit');
    Route::put('/showtimes/{showtime}', [ShowtimeController::class, 'update'])->name('showtimes.update');
    Route::delete('/showtimes/{showtime}', [ShowtimeController::class, 'destroy'])->name('showtimes.destroy');
    Route::put('/showtimes/{showtime}/toggle-active', [ShowtimeController::class, 'toggleActive'])->name('showtimes.toggleActive');

    // Kelola Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{booking}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/transactions/{booking}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

    // Laporan & Rekapitulasi
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');

    // Kelola User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
});

Route::middleware('auth')->group(function () {
    // Settings (Privat) — harus sebelum /profile/{user} agar tidak tertangkap
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Email Change with Verification
    Route::post('/profile/email/change', [EmailChangeController::class, 'requestChange'])->name('email-change.request');
    Route::delete('/profile/email/cancel', [EmailChangeController::class, 'cancel'])->name('email-change.cancel');

    // Profile (Publik)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}', [ProfileController::class, 'userProfile'])->name('profile.user');

    // Daftar Film (Sesuai B_Flow.md, hanya yang login bisa akses)
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/now-showing', [MovieController::class, 'nowShowing'])->name('movies.nowShowing');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

    // Ulasan
    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Watchlist
    Route::post('/movies/{movie}/watchlist', [CollectionController::class, 'toggleWatchlist'])->name('watchlist.toggle');
    Route::delete('/movies/{movie}/watchlist', [CollectionController::class, 'removeWatchlist'])->name('watchlist.remove');

    // Diary
    Route::post('/movies/{movie}/diary', [CollectionController::class, 'addDiary'])->name('diary.add');
    Route::delete('/movies/{movie}/diary', [CollectionController::class, 'removeDiary'])->name('diary.remove');

    // Koleksi Saya
    Route::get('/collection', [CollectionController::class, 'index'])->name('collection.index');

    // Booking
    Route::get('/movies/{movie}/book', [BookingController::class, 'selectShowtime'])->name('bookings.selectShowtime');
    Route::get('/bookings/{showtime}/seats', [BookingController::class, 'selectSeats'])->name('bookings.selectSeats');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
    Route::put('/bookings/{booking}/pay', [BookingController::class, 'pay'])->name('bookings.pay');
    Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/print', [BookingController::class, 'print'])->name('bookings.print');
    Route::get('/riwayat', [BookingController::class, 'history'])->name('bookings.history');
});

// Email Change Verification (tanpa auth middleware karena link dari email)
Route::get('/email-change/verify', [EmailChangeController::class, 'verify'])->name('email-change.verify');

require __DIR__.'/auth.php';
