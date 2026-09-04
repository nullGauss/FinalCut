<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Profile</h1>
    </x-slot>

    <div class="section">
        @if (session('status') === 'profile-updated')
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                Profile berhasil diperbarui!
            </div>
        @endif

        <div class="max-w-lg mx-auto space-y-6">
            <!-- Profile Info -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Informasi Profile</h3>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="label">Nama <span class="text-red-600">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="input" required autofocus>
                            @error('name') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="label">Email <span class="text-red-600">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="input" required>
                            @error('email') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="label">Role</label>
                            <div class="input bg-background cursor-not-allowed">{{ ucfirst($user->role) }}</div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Ubah Password</h3>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="label">Password Saat Ini <span class="text-red-600">*</span></label>
                            <input type="password" id="current_password" name="current_password" class="input" required>
                            @error('current_password', 'updatePassword') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="label">Password Baru <span class="text-red-600">*</span></label>
                            <input type="password" id="password" name="password" class="input" required>
                            @error('password', 'updatePassword') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="label">Konfirmasi Password Baru <span class="text-red-600">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </div>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="card p-6 border-red-600">
                <h3 class="font-display font-bold text-lg text-red-600 mb-2">Hapus Akun</h3>
                <p class="text-sm text-ink-secondary mb-4">Setelah akun dihapus, semua data akan dihapus permanen. Pastikan kamu yakin.</p>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="password_delete" class="label">Masukkan password untuk konfirmasi</label>
                        <input type="password" id="password_delete" name="password" class="input" required>
                        @error('password', 'userDeletion') <p class="error-text mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white"
                            onclick="return confirm('Yakin ingin menghapus akun secara permanen?')">
                        Hapus Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
