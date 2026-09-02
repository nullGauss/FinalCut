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
                <div class="flex items-end">
                    <button type="submit" class="btn btn-outline btn-sm h-[34px]">Filter</button>
                    @if(request('date') || request('cinema'))
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
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Harga Tiket</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($showtimes as $show)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
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
                                <td class="px-4 py-3 text-right font-medium text-ink">
                                    Rp {{ number_format($show->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openModal('edit', {{ $show->id }})" class="btn btn-outline btn-sm">Edit</button>
                                        <form method="POST" action="{{ route('admin.showtimes.destroy', $show) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-ink-secondary">
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
                        
                        // Extract only date string if it comes with time
                        const dateVal = data.show_date.split('T')[0];
                        document.getElementById('show_date').value = dateVal || '';
                        
                        // Extract HH:mm from time string
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
    </script>
    @endpush
</x-app-layout>
