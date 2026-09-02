<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline btn-sm">
                &larr; Kembali
            </a>
            <div>
                <h1 class="font-display font-bold text-2xl text-ink">Studio & Kursi</h1>
                <p class="text-sm text-ink-secondary">{{ $cinema->name }} ({{ $cinema->city }})</p>
            </div>
            <div class="ml-auto">
                <button onclick="openModal()" class="btn btn-primary btn-sm">
                    + Tambah Studio
                </button>
            </div>
        </div>
    </x-slot>

    <div class="section">
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
                            <form method="POST" action="{{ route('admin.cinemas.studios.destroy', [$cinema, $studio]) }}" onsubmit="return confirm('Hapus studio ini beserta seluruh kursinya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Hapus Studio</button>
                            </form>
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
    </script>
    @endpush
</x-app-layout>
