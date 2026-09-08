<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmailChangeController extends Controller
{
    public function showForm(): View
    {
        $user = Auth::user();

        return view('profile.email-change', compact('user'));
    }

    public function requestChange(Request $request): RedirectResponse
    {
        $request->validate([
            'new_email' => 'required|email|max:255',
            'password' => 'required|current_password',
        ]);

        $user = $request->user();

        if ($user->email === $request->new_email) {
            return back()->withErrors(['new_email' => 'Email baru sama dengan email saat ini.']);
        }

        $existingUser = User::where('email', $request->new_email)->first();
        if ($existingUser && $existingUser->id !== $user->id) {
            return back()->withErrors(['new_email' => 'Email sudah digunakan oleh akun lain.']);
        }

        $token = Str::random(64);

        $user->update([
            'pending_email' => $request->new_email,
            'email_change_token' => $token,
        ]);

        $verificationUrl = route('email-change.verify', [
            'token' => $token,
            'email' => $request->new_email,
        ]);

        return redirect()->route('profile.settings')
            ->with('status', 'email-change-sent')
            ->with('pendingEmail', $request->new_email);
    }

    public function verify(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('profile.settings')
                ->withErrors(['email' => 'Link verifikasi tidak valid.']);
        }

        $user = User::where('email_change_token', $token)
                     ->where('pending_email', $email)
                     ->first();

        if (!$user) {
            return redirect()->route('profile.settings')
                ->withErrors(['email' => 'Link verifikasi tidak valid atau sudah kedaluwarsa.']);
        }

        $user->update([
            'email' => $email,
            'pending_email' => null,
            'email_change_token' => null,
        ]);

        return redirect()->route('profile.settings')
            ->with('status', 'email-changed');
    }

    public function cancel(): RedirectResponse
    {
        Auth::user()->update([
            'pending_email' => null,
            'email_change_token' => null,
        ]);

        return redirect()->route('profile.settings')
            ->with('status', 'email-change-cancelled');
    }
}
