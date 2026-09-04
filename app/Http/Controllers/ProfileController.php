<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // =====================
    // PROFILE PAGE (PUBLIK)
    // =====================

    public function show(Request $request)
    {
        $user = $request->user();

        $stats = [
            'reviews' => $user->reviews()->count(),
            'bookings' => $user->bookings()->count(),
            'watchlist' => $user->watchlist()->count(),
            'diary' => $user->watchedDiary()->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    public function userProfile(User $user)
    {
        $stats = [
            'reviews' => $user->reviews()->count(),
            'bookings' => $user->bookings()->count(),
            'watchlist' => $user->watchlist()->count(),
            'diary' => $user->watchedDiary()->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    // =======================
    // SETTINGS PAGE (PRIVAT)
    // =======================

    public function settings()
    {
        return view('profile.settings', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bio' => 'nullable|string|max:300',
        ]);

        $request->user()->update([
            'name' => $request->name,
            'bio' => $request->bio,
        ]);

        return Redirect::route('profile.settings')->with('status', 'profile-updated');
    }

    // ==================
    // FOTO PROFIL
    // ==================

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();

        if ($user->foto && \file_exists(public_path('uploads/avatars/' . $user->foto))) {
            \unlink(public_path('uploads/avatars/' . $user->foto));
        }

        $file = $request->file('foto');
        $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/avatars'), $filename);

        $user->update(['foto' => $filename]);

        return Redirect::route('profile.settings')->with('status', 'foto-updated');
    }

    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->foto && \file_exists(public_path('uploads/avatars/' . $user->foto))) {
            \unlink(public_path('uploads/avatars/' . $user->foto));
        }

        $user->update(['foto' => null]);

        return Redirect::route('profile.settings')->with('status', 'foto-deleted');
    }

    // ==================
    // GANTI PASSWORD
    // ==================

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => $request->password,
        ]);

        return Redirect::route('profile.settings')->with('status', 'password-updated');
    }

    // ==================
    // HAPUS AKUN
    // ==================

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->foto && \file_exists(public_path('uploads/avatars/' . $user->foto))) {
            \unlink(public_path('uploads/avatars/' . $user->foto));
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
