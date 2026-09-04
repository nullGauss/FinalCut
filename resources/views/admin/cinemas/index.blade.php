<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">
                &larr; Kembali
            </a>
            <h1 class="font-display font-bold text-2xl text-ink">Kelola Bioskop</h1>
            <div class="ml-auto">
                <button onclick="openModal('add')" class="btn btn-primary btn-sm">
                    + Tambah Bioskop
                </button>
            </div>
        </div>
    </x-slot>

    <div class="section">
        <!-- Flash Message -->
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <form method="GET" class="card p-4 mb-6">
            <div class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input flex-1" placeholder="Cari nama atau kota bioskop...">
                <button type="submit" class="btn btn-outline btn-sm">Cari</button>
            </div>
        </form>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-1.5 border-ink bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Nama Bioskop</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Kota</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Alamat</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Total Studio</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cinemas as $cinema)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3 font-medium text-ink">{{ $cinema->name }}</td>
                                <td class="px-4 py-3 text-ink-secondary">{{ $cinema->city }}</td>
                                <td class="px-4 py-3 text-ink-secondary">{{ \Illuminate\Support\Str::limit($cinema->address, 50) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge-blue">{{ $cinema->studios_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.cinemas.studios', $cinema) }}" class="btn btn-yellow btn-sm">
                                            Kelola Studio
                                        </a>
                                        <button onclick="openModal('edit', {{ $cinema->id }})"
                                                class="btn btn-outline btn-sm">
                                            Edit
                                        </button>
                                        <button onclick="openDeleteCinemaModal({{ $cinema->id }})" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-ink-secondary">
                                    Belum ada data bioskop.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($cinemas->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $cinemas->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah/Edit Bioskop -->
    <div id="cinemaModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-lg bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 id="modalTitle" class="font-display font-bold text-lg text-ink">Tambah Bioskop</h2>
                <button onclick="closeModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="cinemaForm" method="POST" class="p-6 space-y-4">
                @csrf
                <div id="formMethod"></div>

                <div>
                    <label for="name" class="label">Nama Bioskop <span class="text-red-600">*</span></label>
                    <input type="text" id="name" name="name" class="input" required maxlength="100" placeholder="FinalCut Cineplex">
                </div>

                <div>
                    <label for="city" class="label">Kota <span class="text-red-600">*</span></label>
                    <input type="text" id="city" name="city" class="input" required maxlength="100" placeholder="Bandung">
                </div>

                <div>
                    <label for="address" class="label">Alamat Lengkap <span class="text-red-600">*</span></label>
                    <textarea id="address" name="address" class="input" required rows="3"></textarea>
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
        function openModal(mode, id = null) {
            const modal = document.getElementById('cinemaModal');
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('cinemaForm');
            const methodDiv = document.getElementById('formMethod');

            form.reset();

            if (mode === 'add') {
                title.textContent = 'Tambah Bioskop';
                methodDiv.innerHTML = '';
                form.action = '{{ route("admin.cinemas.store") }}';
            } else {
                title.textContent = 'Edit Bioskop';
                methodDiv.innerHTML = '@method("PUT")';
                form.action = `/admin/cinemas/${id}`;

                fetch(`/admin/cinemas/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('name').value = data.name || '';
                        document.getElementById('city').value = data.city || '';
                        document.getElementById('address').value = data.address || '';
                    });
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('cinemaModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
