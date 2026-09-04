<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Studio & Kursi</h1>
            <button onclick="openModal()" class="btn btn-primary btn-sm">
                + Tambah Studio
            </button>
        </div>
    </x-slot>

    <div class="section">
        <x-breadcrumb :items="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Kelola Bioskop', 'url' => route('admin.cinemas.index')],
            ['label' => $cinema->name],
        ]" />

        @if (session('success'))
            <div class="card-sm p-4 mb-6 bg-green-50 border-green-600 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-[300px_1fr] gap-8 items-start">
            <!-- Sidebar: Info Bioskop -->
            <div class="card p-6">
                <h3 class="font-display font-bold text-lg text-ink mb-4">Informasi Bioskop</h3>
                <div class="space-y-4 text-sm text-ink-secondary">
                    <div>
                        <strong class="block text-ink">Nama</strong>
                        {{ $cinema->name }}
                    </div>
                    <div>
                        <strong class="block text-ink">Kota</strong>
                        {{ $cinema->city }}
                    </div>
                    <div>
                        <strong class="block text-ink">Alamat</strong>
                        {{ $cinema->address }}
                    </div>
                    <hr class="border-gray-200">
                    <div>
                        <strong class="block text-ink">Total Studio</strong>
                        {{ $cinema->studios->count() }} Studio
                    </div>
                </div>
            </div>

            <!-- Main: List Studio -->
            <div class="space-y-6">
                @forelse ($cinema->studios as $studio)
                    <div class="card p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-display font-bold text-xl text-ink">{{ $studio->name }}</h3>
                                <p class="text-sm text-ink-secondary">Kapasitas: {{ $studio->capacity }} Kursi</p>
                            </div>
                            <button type="button" onclick="openDeleteStudioModal({{ $studio->id }})" class="text-sm text-red-600 hover:underline">Hapus Studio</button>
                        </div>
                        
                        <!-- Map Visualisasi Ringkas Kursi -->
                        <div class="bg-gray-50 border border-gray-200 rounded p-4 overflow-x-auto">
                            <div class="w-full text-center text-xs font-bold text-gray-400 mb-4 pb-2 border-b border-gray-300">LAYAR BIOSKOP</div>
                            
                            @php
                                // Kelompokkan kursi berdasarkan huruf baris depannya (A, B, C...)
                                $seatsByRow = $studio->seats->groupBy(function($seat) {
                                    return substr($seat->seat_number, 0, 1);
                                });
                            @endphp

                            <div class="flex flex-col gap-2 items-center min-w-max">
                                @foreach ($seatsByRow as $row => $seats)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold w-4 text-center">{{ $row }}</span>
                                        <div class="flex gap-1">
                                            @foreach ($seats as $seat)
                                                <div class="w-6 h-6 flex items-center justify-center text-[10px] rounded {{ $seat->seat_type === 'vip' ? 'bg-yellow-bg text-yellow-text border border-yellow-text' : 'bg-surface border border-ink text-ink' }}" title="{{ $seat->seat_number }} ({{ strtoupper($seat->seat_type) }})">
                                                    {{ substr($seat->seat_number, 1) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="flex items-center justify-center gap-4 mt-6 text-xs text-ink-secondary">
                                <div class="flex items-center gap-1"><div class="w-3 h-3 bg-surface border border-ink rounded"></div> Reguler</div>
                                <div class="flex items-center gap-1"><div class="w-3 h-3 bg-yellow-bg border border-yellow-text rounded"></div> VIP</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card p-12 text-center">
                        <p class="text-ink-secondary mb-4">Bioskop ini belum memiliki studio.</p>
                        <button onclick="openModal()" class="btn btn-primary btn-sm">Tambah Studio Pertama</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Tambah Studio -->
    <div id="studioModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-md bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b-1.5 border-ink">
                <h2 class="font-display font-bold text-lg text-ink">Tambah Studio Baru</h2>
                <button onclick="closeModal()" class="text-ink-secondary hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('admin.cinemas.studios.store', $cinema) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="name" class="label">Nama Studio <span class="text-red-600">*</span></label>
                    <input type="text" id="name" name="name" class="input" required maxlength="50" placeholder="Studio 1 / Premiere / IMAX">
                </div>

                <div class="bg-blue-50 border border-blue-200 p-4 rounded text-sm text-blue-800 mb-4">
                    <strong>Auto-Generate Kursi:</strong> Tentukan jumlah Baris dan Kolom, sistem akan membuat denah kursi (A1, A2, dst). Baris pertama otomatis jadi VIP.
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="rows" class="label">Jumlah Baris (A-Z) <span class="text-red-600">*</span></label>
                        <input type="number" id="rows" name="rows" class="input" required min="1" max="26" value="5">
                    </div>
                    <div>
                        <label for="cols" class="label">Kursi per Baris <span class="text-red-600">*</span></label>
                        <input type="number" id="cols" name="cols" class="input" required min="1" max="50" value="8">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="btn btn-outline btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Generate Studio</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Studio -->
    <div id="deleteStudioModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeDeleteStudioModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Hapus Studio?</h3>
                <p class="text-ink-secondary text-sm mb-6">Seluruh kursi di studio ini akan ikut terhapus.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteStudioModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <form id="deleteStudioForm" method="POST" class="flex-1">
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
        function openModal() {
            document.getElementById('studioModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('studioModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openDeleteStudioModal(id) {
            const form = document.getElementById('deleteStudioForm');
            form.action = `/admin/cinemas/{{ $cinema->id }}/studios/${id}`;
            document.getElementById('deleteStudioModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteStudioModal() {
            document.getElementById('deleteStudioModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
