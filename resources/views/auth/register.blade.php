<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-display font-bold text-2xl text-ink">Daftar Akun Baru</h1>
        <p class="text-ink-secondary text-sm mt-1">Gabung komunitas FinalCut</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="label">Nama</label>
            <input id="name" class="input @error('name') input-error @enderror"
                   type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap">
            @error('name')
                <p class="error-text mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mt-4">
            <label for="email" class="label">Email</label>
            <input id="email" class="input @error('email') input-error @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="email@contoh.com">
            @error('email')
                <p class="error-text mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="label">Password</label>
            <input id="password" class="input @error('password') input-error @enderror"
                   type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            @error('password')
                <p class="error-text mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="label">Konfirmasi Password</label>
            <input id="password_confirmation" class="input @error('password_confirmation') input-error @enderror"
                   type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
            @error('password_confirmation')
                <p class="error-text mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="btn btn-primary w-full justify-center">
                Daftar
            </button>
        </div>
    </form>

    <!-- Login link -->
    <p class="mt-6 text-center text-sm text-ink-secondary">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-blue-text hover:underline">Masuk</a>
    </p>
</x-guest-layout>
