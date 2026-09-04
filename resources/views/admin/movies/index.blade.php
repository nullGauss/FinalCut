<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Kelola Film</h1>
            <button onclick="openModal('add')" class="btn btn-primary btn-sm">
                + Tambah Film
            </button>
        </div>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Kelola Film'],
        ]" />

        <!-- Flash Message -->
        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search & Filter -->
        <form method="GET" class="card p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input flex-1" placeholder="Cari judul atau sutradara...">
                <select name="genre" class="input w-auto sm:w-48">
                    <option value="">Semua Genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline btn-sm">Cari</button>
            </div>
        </form>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-1.5 border-ink bg-background">
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Judul</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Genre</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Durasi</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Rating Umur</th>
                            <th class="text-left px-4 py-3 font-display font-bold text-ink">Sutradara</th>
                            <th class="text-right px-4 py-3 font-display font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movies as $movie)
                            <tr class="border-b border-gray-200 hover:bg-background transition-colors">
                                <td class="px-4 py-3 font-medium text-ink">{{ $movie->title }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($movie->genres as $genre)
                                            <span class="badge-blue text-xs">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-ink-secondary">{{ $movie->durasi ? $movie->durasi . ' menit' : '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge-yellow">{{ $movie->rating_umur }}</span>
                                </td>
                                <td class="px-4 py-3 text-ink-secondary">{{ $movie->director ?: '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openModal('edit', {{ $movie->id }})"
                                                class="btn btn-outline btn-sm">
                                            Edit
                                        </button>
                                        <button onclick="openDeleteModal({{ $movie->id }})" class="btn btn-sm border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-ink-secondary">
                                    Belum ada data film.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($movies->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $movies->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah/Edit Film -->
    <div id="movieModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-2xl sm:max-h-[90vh] bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 id="modalTitle" class="font-display font-bold text-lg text-ink">Tambah Film</h2>
                <button onclick="closeModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="movieForm" method="POST" class="overflow-y-auto flex-1 p-6">
                @csrf
                <div id="formMethod"></div>

                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="label">Judul Film <span class="text-red-600">*</span></label>
                        <input type="text" id="title" name="title" class="input" required maxlength="150">
                    </div>

                    <!-- Director -->
                    <div>
                        <label for="director" class="label">Sutradara</label>
                        <input type="text" id="director" name="director" class="input" maxlength="100">
                    </div>

                    <!-- Synopsis -->
                    <div>
                        <label for="sinopsis" class="label">Sinopsis</label>
                        <textarea id="sinopsis" name="sinopsis" class="input" rows="3"></textarea>
                    </div>

                    <!-- Duration & Rating -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="durasi" class="label">Durasi (menit)</label>
                            <input type="number" id="durasi" name="durasi" class="input" min="1">
                        </div>
                        <div>
                            <label for="rating_umur" class="label">Rating Umur</label>
                            <select id="rating_umur" name="rating_umur" class="input">
                                <option value="SU">SU</option>
                                <option value="13+">13+</option>
                                <option value="17+">17+</option>
                                <option value="21+">21+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Release Date -->
                    <div>
                        <label for="release_date" class="label">Tanggal Rilis</label>
                        <input type="date" id="release_date" name="release_date" class="input">
                    </div>

                    <!-- Poster URL -->
                    <div>
                        <label for="poster" class="label">URL Poster</label>
                        <input type="text" id="poster" name="poster" class="input" maxlength="255" placeholder="https://...">
                    </div>

                    <!-- Trailer URL -->
                    <div>
                        <label for="trailer_url" class="label">URL Trailer</label>
                        <input type="text" id="trailer_url" name="trailer_url" class="input" maxlength="255" placeholder="https://youtube.com/...">
                    </div>

                    <!-- Genres -->
                    <div>
                        <label class="label">Genre <span class="text-red-600">*</span></label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($genres as $genre)
                                <label class="inline-flex items-center gap-2 px-3 py-1.5 border-1.5 border-ink rounded-full cursor-pointer hover:bg-background transition-colors">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                           class="genre-checkbox w-4 h-4 border-ink rounded text-ink focus:ring-ink">
                                    <span class="text-sm text-ink">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="btn btn-outline btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Film -->
    <div id="deleteMovieModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDeleteModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Hapus Film?</h3>
                <p class="text-ink-secondary text-sm mb-6">Film dan semua data terkait (ulasan, jadwal) akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <form id="deleteMovieForm" method="POST" class="flex-1">
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
        let modalMode = 'add';

        function openModal(mode, movieId = null) {
            modalMode = mode;
            const modal = document.getElementById('movieModal');
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('movieForm');
            const methodDiv = document.getElementById('formMethod');

            // Reset form
            form.reset();
            document.querySelectorAll('.genre-checkbox').forEach(cb => cb.checked = false);

            if (mode === 'add') {
                title.textContent = 'Tambah Film';
                methodDiv.innerHTML = '';
                form.action = '{{ route("admin.movies.store") }}';
            } else {
                title.textContent = 'Edit Film';
                methodDiv.innerHTML = '@method("PUT")';
                form.action = `/admin/movies/${movieId}`;

                // Fetch movie data via AJAX
                fetch(`/admin/movies/${movieId}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        const movie = data.movie;
                        document.getElementById('title').value = movie.title || '';
                        document.getElementById('director').value = movie.director || '';
                        document.getElementById('sinopsis').value = movie.sinopsis || '';
                        document.getElementById('durasi').value = movie.durasi || '';
                        document.getElementById('rating_umur').value = movie.rating_umur || 'SU';
                        document.getElementById('release_date').value = movie.release_date || '';
                        document.getElementById('poster').value = movie.poster || '';
                        document.getElementById('trailer_url').value = movie.trailer_url || '';

                        // Check genres
                        data.selected_genres.forEach(id => {
                            const cb = document.querySelector(`.genre-checkbox[value="${id}"]`);
                            if (cb) cb.checked = true;
                        });
                    });
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('movieModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteMovieForm');
            form.action = `/admin/movies/${id}`;
            document.getElementById('deleteMovieModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteMovieModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
