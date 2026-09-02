<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/movies', [AdminMovieController::class, 'index'])->name('movies.index');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{movie}/edit', [AdminMovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{movie}', [AdminMovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{movie}', [AdminMovieController::class, 'destroy'])->name('movies.destroy');

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

    // Kelola Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{booking}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/transactions/{booking}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

    // Laporan & Rekapitulasi
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
});

Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daftar Film (Sesuai B_Flow.md, hanya yang login bisa akses)
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

    // Ulasan
    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

require __DIR__.'/auth.php';
