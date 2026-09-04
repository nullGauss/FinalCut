<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Kelola Jadwal Tayang</h1>
            <button onclick="openModal('add')" class="btn btn-primary btn-sm">
                + Tambah Jadwal
            </button>
        </div>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Kelola Jadwal Tayang'],
        ]" />

        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="card-sm p-4 mb-6 bg-red-50 border-red-600 text-red-800">
                Ada kesalahan input. Silakan cek form kembali.
            </div>
        @endif

        <!-- Filter -->
        <form method="GET" class="card p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Pilih Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="input py-1.5 text-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Filter Bioskop</label>
                    <select name="cinema" class="input py-1.5 text-sm">
                        <option value="">Semua Bioskop</option>
                        @foreach ($cinemas as $c)
                            <option value="{{ $c->id }}" {{ request('cinema') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-ink-secondary mb-1">Status</label>
                    <select name="status" class="input py-1.5 text-sm">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif (Tayang)</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif (Selesai)</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-outline btn-sm h-[34px]">Filter</button>
                    @if(request('date') || request('cinema') || request('status'))
                        <a href="{{ route('admin.showtimes.index') }}" class="ml-2 text-sm text-blue-text hover:underline mb-2">Reset</a>
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
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Tanggal & Waktu</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Film</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Bioskop / Studio</th>
                            <th class="text-center px-4 py-3 font-display font-bold text-ink">Status</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Harga Tiket</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($showtimes as $show)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors {{ !$show->is_active ? 'opacity-60' : '' }}">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-ink">{{ $show->show_date->format('d M Y') }}</div>
                                    <div class="text-ink-secondary">{{ \Carbon\Carbon::parse($show->show_time)->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-ink">{{ $show->movie->title }}</div>
                                    <span class="badge-yellow text-[10px]">{{ $show->movie->rating_umur }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-ink">{{ $show->studio->cinema->name }}</div>
                                    <div class="text-xs text-ink-secondary">{{ $show->studio->name }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($show->is_active)
                                        <span class="badge-green">Aktif</span>
                                    @else
                                        <span class="badge-red">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-ink">
                                    Rp {{ number_format($show->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openModal('edit', {{ $show->id }})" class="btn btn-outline btn-sm">Edit</button>
                                        @if ($show->is_active)
                                            <button onclick="openDeactivateModal({{ $show->id }})" class="btn btn-sm border-yellow-text text-yellow-text hover:bg-yellow-bg">
                                                Selesai
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.showtimes.toggleActive', $show) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm border-green-600 text-green-600 hover:bg-green-50">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                        <button onclick="openDeleteModal({{ $show->id }})" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-ink-secondary">
                                    Tidak ada jadwal tayang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($showtimes->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $showtimes->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah/Edit Jadwal -->
    <div id="showModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-lg bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 id="modalTitle" class="font-display font-bold text-lg text-ink">Tambah Jadwal</h2>
                <button onclick="closeModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="showForm" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                @csrf
                <div id="formMethod"></div>

                <div>
                    <label for="movie_id" class="label">Pilih Film <span class="text-red-600">*</span></label>
                    <select id="movie_id" name="movie_id" class="input" required>
                        <option value="">-- Pilih Film --</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->rating_umur }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="studio_id" class="label">Pilih Bioskop & Studio <span class="text-red-600">*</span></label>
                    <select id="studio_id" name="studio_id" class="input" required>
                        <option value="">-- Pilih Studio --</option>
                        @foreach ($cinemas as $cinema)
                            <optgroup label="{{ $cinema->name }}">
                                @foreach ($cinema->studios as $studio)
                                    <option value="{{ $studio->id }}">{{ $studio->name }} (Kap: {{ $studio->capacity }})</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="show_date" class="label">Tanggal Tayang <span class="text-red-600">*</span></label>
                        <input type="date" id="show_date" name="show_date" class="input" required>
                    </div>
                    <div>
                        <label for="show_time" class="label">Jam (WIB) <span class="text-red-600">*</span></label>
                        <input type="time" id="show_time" name="show_time" class="input" required>
                    </div>
                </div>

                <div>
                    <label for="price" class="label">Harga Tiket (Rp) <span class="text-red-600">*</span></label>
                    <input type="number" id="price" name="price" class="input" required min="0" step="1000" placeholder="50000">
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="btn btn-outline btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Nonaktifkan -->
    <div id="deactivateModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDeactivateModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Nonaktifkan Jadwal?</h3>
                <p class="text-ink-secondary text-sm mb-6">Film tidak akan tayang lagi, namun riwayat booking tetap tersimpan.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeactivateModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <form id="deactivateForm" method="POST" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm w-full justify-center border-yellow-text text-yellow-text hover:bg-yellow-bg">Ya, Nonaktifkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDeleteModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Hapus Jadwal?</h3>
                <p class="text-ink-secondary text-sm mb-6">Data jadwal dan riwayat booking terkait akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm w-full justify-center border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(mode, id = null) {
            const modal = document.getElementById('showModal');
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('showForm');
            const methodDiv = document.getElementById('formMethod');

            form.reset();

            if (mode === 'add') {
                title.textContent = 'Tambah Jadwal Tayang';
                methodDiv.innerHTML = '';
                form.action = '{{ route("admin.showtimes.store") }}';
            } else {
                title.textContent = 'Edit Jadwal Tayang';
                methodDiv.innerHTML = '@method("PUT")';
                form.action = `/admin/showtimes/${id}`;

                fetch(`/admin/showtimes/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('movie_id').value = data.movie_id || '';
                        document.getElementById('studio_id').value = data.studio_id || '';
                        
                        const dateVal = data.show_date.split('T')[0];
                        document.getElementById('show_date').value = dateVal || '';
                        
                        const timeVal = data.show_time.substring(0, 5);
                        document.getElementById('show_time').value = timeVal || '';
                        
                        document.getElementById('price').value = parseInt(data.price) || '';
                    });
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('showModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openDeactivateModal(id) {
            const form = document.getElementById('deactivateForm');
            form.action = `/admin/showtimes/${id}/toggle-active`;
            document.getElementById('deactivateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeactivateModal() {
            document.getElementById('deactivateModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/showtimes/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
