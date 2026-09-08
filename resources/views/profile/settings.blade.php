<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Pengaturan Profile</h1>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Profile', 'url' => route('profile.show')],
            ['label' => 'Pengaturan'],
        ]" />

        @if (session('status') === 'profile-updated')
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">Profile berhasil diperbarui!</div>
        @endif
        @if (session('status') === 'foto-updated')
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">Foto profil berhasil diperbarui!</div>
        @endif
        @if (session('status') === 'foto-deleted')
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">Foto profil berhasil dihapus.</div>
        @endif
        @if (session('status') === 'email-changed')
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">Email berhasil diubah!</div>
        @endif
        @if (session('status') === 'email-change-cancelled')
            <div class="card-sm p-4 mb-6 bg-yellow-50 border-yellow-600 text-yellow-800">Perubahan email dibatalkan.</div>
        @endif
        @if (session('status') === 'email-change-sent')
            <div class="card-sm p-4 mb-6 bg-blue-50 border-blue-text text-blue-text">
                Link verifikasi sudah dikirim ke <span class="font-medium">{{ session('pendingEmail') }}</span>. Cek inbox/kotak spam kamu.
            </div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">Password berhasil diubah!</div>
        @endif

        <div class="max-w-lg mx-auto space-y-6">

            <!-- Edit Profil -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Edit Profil</h3>

                <!-- Avatar -->
                <div class="flex items-center gap-6 mb-6">
                    <div class="shrink-0">
                        @if ($user->foto)
                            <img id="avatarPreview" src="{{ asset('uploads/avatars/' . $user->foto) }}" alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-full border-1.5 border-ink object-cover">
                        @else
                            <div id="avatarPreview" class="w-20 h-20 rounded-full border-1.5 border-ink bg-blue-bg text-blue-text flex items-center justify-center font-display font-bold text-2xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <form id="photoForm" method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="document.getElementById('fotoInput').click()" class="btn btn-outline btn-sm">Pilih Foto</button>
                            </div>
                            <span id="fileName" class="text-xs text-ink-secondary mt-1 block"></span>
                            <div id="uploadActions" class="hidden mt-2 flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                <button type="button" onclick="cancelPhoto()" class="btn btn-outline btn-sm">Batal</button>
                            </div>
                            @error('foto') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </form>
                        @if ($user->foto)
                            <form method="POST" action="{{ route('profile.photo.delete') }}" class="inline mt-2" onsubmit="return confirm('Hapus foto profil?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Hapus Foto</button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Name & Bio -->
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="label">Nama <span class="text-red-600">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="input" required maxlength="100">
                            @error('name') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="bio" class="label">Bio</label>
                            <textarea id="bio" name="bio" rows="3" class="input" maxlength="300" placeholder="Ceritakan tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                            <p class="text-xs text-ink-secondary mt-1">Maks 300 karakter</p>
                            @error('bio') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Ganti Email -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-2">Ganti Email</h3>
                <p class="text-sm text-ink-secondary mb-4">Email saat ini: <span class="font-medium text-ink">{{ $user->email }}</span></p>

                @if ($user->pending_email)
                    <div class="card-sm p-4 bg-yellow-50 border-yellow-600 mb-4">
                        <p class="text-sm text-yellow-800">
                            Email verifikasi sudah dikirim ke <span class="font-medium">{{ $user->pending_email }}</span>.
                        </p>
                        <p class="text-xs text-yellow-700 mt-1">
                            Cek inbox/kotak spam kamu untuk link verifikasi.
                        </p>
                        <div class="mt-3 flex gap-2">
                            <form method="POST" action="{{ route('email-change.request') }}" class="inline">
                                @csrf
                                <input type="hidden" name="new_email" value="{{ $user->pending_email }}">
                                <input type="hidden" name="password" value="">
                                <button type="submit" class="text-xs text-yellow-700 hover:underline">Kirim ulang</button>
                            </form>
                            <span class="text-yellow-600">|</span>
                            <form method="POST" action="{{ route('email-change.cancel') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Batalkan</button>
                            </form>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('email-change.request') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="new_email" class="label">Email Baru <span class="text-red-600">*</span></label>
                                <input type="email" id="new_email" name="new_email" class="input" required maxlength="255"
                                       placeholder="email-baru@contoh.com" value="{{ old('new_email') }}">
                                @error('new_email') <p class="error-text mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email_password" class="label">Password untuk Konfirmasi <span class="text-red-600">*</span></label>
                                <input type="password" id="email_password" name="password" class="input" required>
                                @error('password') <p class="error-text mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Kirim Link Verifikasi</button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Ganti Password -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Ganti Password</h3>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="label">Password Saat Ini <span class="text-red-600">*</span></label>
                            <input type="password" id="current_password" name="current_password" class="input" required>
                            @error('current_password') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="label">Password Baru <span class="text-red-600">*</span></label>
                            <input type="password" id="password" name="password" class="input" required minlength="8">
                            @error('password') <p class="error-text mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="label">Konfirmasi Password Baru <span class="text-red-600">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="input" required minlength="8">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </div>
                </form>
            </div>

            <!-- Hapus Akun -->
            <div class="card p-6 border-red-600">
                <h3 class="font-display font-bold text-lg text-red-600 mb-2">Hapus Akun</h3>
                <p class="text-sm text-ink-secondary mb-4">Semua data akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="password_delete" class="label">Masukkan password untuk konfirmasi</label>
                        <input type="password" id="password_delete" name="password" class="input" required>
                        @error('password', 'userDeletion') <p class="error-text mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="button" onclick="openDeleteAccountModal()" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                        Hapus Akun
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal Hapus Akun -->
    <div id="deleteAccountModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDeleteAccountModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 class="font-display font-bold text-lg text-ink">Hapus Akun?</h2>
                <button onclick="closeDeleteAccountModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-red-50 border-1.5 border-red-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-ink">Semua data kamu akan dihapus permanen.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeDeleteAccountModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <button onclick="submitDeleteAccount()" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white flex-1">Ya, Hapus Akun</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.id = 'avatarPreview';
                        img.src = e.target.result;
                        img.className = 'w-20 h-20 rounded-full border-1.5 border-ink object-cover';
                        img.alt = 'Preview';
                        preview.replaceWith(img);
                    }
                };
                reader.readAsDataURL(input.files[0]);
                document.getElementById('fileName').textContent = input.files[0].name;
                document.getElementById('uploadActions').classList.remove('hidden');
            }
        }

        function cancelPhoto() {
            document.getElementById('fotoInput').value = '';
            document.getElementById('fileName').textContent = '';
            document.getElementById('uploadActions').classList.add('hidden');
        }

        function openDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function submitDeleteAccount() {
            closeDeleteAccountModal();
            document.getElementById('deleteAccountForm').submit();
        }
    </script>
    @endpush
</x-app-layout>
