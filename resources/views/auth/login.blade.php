<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-display font-bold text-2xl text-ink">Masuk ke Akun</h1>
        <p class="text-ink-secondary text-sm mt-1">Selamat datang kembali di FinalCut</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="label">Email</label>
            <input id="email" class="input @error('email') input-error @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="email@contoh.com">
            @error('email')
                <p class="error-text mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="label">Password</label>
            <input id="password" class="input @error('password') input-error @enderror"
                   type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
            @error('password')
                <p class="error-text mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 border-1.5 border-ink rounded text-ink focus:ring-ink" name="remember">
                <span class="text-sm text-ink-secondary">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-text hover:underline" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="btn btn-primary w-full justify-center">
                Log in
            </button>
        </div>
    </form>

    <!-- Register link -->
    <p class="mt-6 text-center text-sm text-ink-secondary">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-medium text-blue-text hover:underline">Daftar sekarang</a>
    </p>
</x-guest-layout>
