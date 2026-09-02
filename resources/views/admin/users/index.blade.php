<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Kelola User</h1>
        </div>
    </x-slot>

    <div class="section">
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="card-sm p-4 mb-6 bg-red-50 border-red-600 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter -->
        <form method="GET" class="card p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Cari Nama/Email</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input py-1.5 text-sm" placeholder="Nama atau email user...">
                </div>
                <div class="w-40">
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Role</label>
                    <select name="role" class="input py-1.5 text-sm">
                        <option value="all">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="w-40">
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Status</label>
                    <select name="status" class="input py-1.5 text-sm">
                        <option value="all">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary btn-sm h-[34px]">Filter</button>
                    @if (request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}" class="ml-2 text-sm text-blue-text hover:underline mb-2">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-1.5 border-ink bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Nama</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Email</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Role</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Status</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Bergabung</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-ink">{{ $user->name }}</div>
                                </td>
                                <td class="px-4 py-3 text-ink-secondary">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($user->role === 'admin')
                                        <span class="badge-ink">Admin</span>
                                    @else
                                        <span class="badge-blue">User</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($user->is_active)
                                        <span class="badge-green">Aktif</span>
                                    @else
                                        <span class="badge-red">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-ink-secondary text-sm">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openEditModal({{ $user->id }})" class="btn btn-outline btn-sm">
                                            Edit
                                        </button>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggleActive', $user) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                @if ($user->is_active)
                                                    <button type="submit" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white"
                                                            onclick="return confirm('Nonaktifkan akun {{ $user->name }}?')">
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm border-green-600 text-green-600 hover:bg-green-600 hover:text-white">
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm border-yellow-text text-yellow-text hover:bg-yellow-bg"
                                                        onclick="return confirm('Reset password {{ $user->name }} ke default?')">
                                                    Reset PW
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-ink-secondary">
                                    Tidak ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-md bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 class="font-display font-bold text-lg text-ink">Edit User</h2>
                <button onclick="closeModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editFormAction" value="">

                <div>
                    <label for="edit_name" class="label">Nama <span class="text-red-600">*</span></label>
                    <input type="text" id="edit_name" name="name" class="input" required maxlength="100">
                </div>

                <div>
                    <label for="edit_email" class="label">Email <span class="text-red-600">*</span></label>
                    <input type="email" id="edit_email" name="email" class="input" required maxlength="100">
                </div>

                <div>
                    <label for="edit_role" class="label">Role <span class="text-red-600">*</span></label>
                    <select id="edit_role" name="role" class="input" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label for="edit_is_active" class="label">Status</label>
                    <select id="edit_is_active" name="is_active" class="input" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="btn btn-outline btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(id) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            form.action = `/admin/users/${id}`;
            document.getElementById('editFormAction').value = id;

            fetch(`/admin/users/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_role').value = data.role || 'user';
                    document.getElementById('edit_is_active').value = data.is_active ? '1' : '0';
                });

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
