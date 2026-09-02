<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('bookings.selectShowtime', $showtime->movie) }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
            <div>
                <h1 class="font-display font-bold text-2xl text-ink">Pilih Kursi</h1>
                <p class="text-sm text-ink-secondary">{{ $showtime->movie->title }} &middot; {{ $showtime->studio->cinema->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="section">
        <div class="grid lg:grid-cols-[1fr_320px] gap-8 items-start">
            <!-- Seat Map -->
            <div class="card p-6">
                <!-- Info Screen -->
                <div class="text-center mb-8">
                    <div class="w-full h-8 bg-ink rounded-t-[50%] mb-2"></div>
                    <p class="text-xs font-bold text-ink-secondary uppercase tracking-wider">Layar Bioskop</p>
                </div>

                <!-- Seats Grid -->
                <div class="flex flex-col gap-2 items-center mb-8">
                    @foreach ($seatsByRow as $row => $seats)
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold w-5 text-center text-ink-secondary">{{ $row }}</span>
                            <div class="flex gap-1.5">
                                @foreach ($seats as $seat)
                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);
                                        $isVip = $seat->seat_type === 'vip';
                                    @endphp
                                    <button type="button"
                                            data-seat-id="{{ $seat->id }}"
                                            data-seat-number="{{ $seat->seat_number }}"
                                            data-seat-type="{{ $seat->seat_type }}"
                                            data-price="{{ $showtime->price }}"
                                            {{ $isBooked ? 'disabled' : '' }}
                                            onclick="toggleSeat(this)"
                                            class="seat-btn w-9 h-9 flex items-center justify-center text-[11px] font-bold rounded border-1.5 transition-all
                                                {{ $isBooked
                                                    ? 'bg-gray-300 border-gray-400 text-gray-500 cursor-not-allowed'
                                                    : ($isVip
                                                        ? 'bg-yellow-bg border-yellow-text text-yellow-text hover:bg-yellow-text hover:text-white cursor-pointer'
                                                        : 'bg-surface border-ink text-ink hover:bg-ink hover:text-surface cursor-pointer')
                                                }}">
                                        {{ substr($seat->seat_number, 1) }}
                                    </button>
                                @endforeach
                            </div>
                            <span class="text-xs font-bold w-5 text-center text-ink-secondary">{{ $row }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Legend -->
                <div class="flex items-center justify-center gap-6 text-xs text-ink-secondary border-t border-gray-200 pt-4">
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 bg-surface border border-ink rounded"></div>
                        <span>Reguler</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 bg-yellow-bg border border-yellow-text rounded"></div>
                        <span>VIP</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 bg-ink border border-ink rounded"></div>
                        <span>Dipilih</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 bg-gray-300 border border-gray-400 rounded"></div>
                        <span>Terjual</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Ringkasan -->
            <div class="space-y-6">
                <!-- Info Jadwal -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Ringkasan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block font-bold text-ink">Film</span>
                            <span class="text-ink-secondary">{{ $showtime->movie->title }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Bioskop</span>
                            <span class="text-ink-secondary">{{ $showtime->studio->cinema->name }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Studio</span>
                            <span class="text-ink-secondary">{{ $showtime->studio->name }}</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Tanggal & Jam</span>
                            <span class="text-ink-secondary">{{ $showtime->show_date->format('d M Y') }}, {{ \Carbon\Carbon::parse($showtime->show_time)->format('H:i') }} WIB</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink">Harga per Kursi</span>
                            <span class="text-ink-secondary">Rp {{ number_format($showtime->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kursi Dipilih -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Kursi Dipilih</h3>
                    <div id="selectedSeats" class="text-sm text-ink-secondary mb-4">
                        Belum ada kursi dipilih
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-ink-secondary">Harga x <span id="seatCount">0</span> kursi</span>
                            <span class="text-ink" id="pricePerSeat">Rp {{ number_format($showtime->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-display font-bold text-lg text-ink">
                            <span>Total</span>
                            <span id="totalPrice">Rp 0</span>
                        </div>
                    </div>
                    <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                        <input type="hidden" name="seat_ids" id="seatIdsInput" value="">
                        <button type="submit" id="submitBtn" disabled
                                class="btn btn-primary btn-sm w-full disabled:opacity-50 disabled:cursor-not-allowed">
                            Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedSeats = [];

        function toggleSeat(btn) {
            const seatId = btn.dataset.seatId;
            const seatNumber = btn.dataset.seatNumber;
            const seatType = btn.dataset.seatType;
            const price = parseInt(btn.dataset.price);

            const index = selectedSeats.findIndex(s => s.id === seatId);

            if (index > -1) {
                selectedSeats.splice(index, 1);
                btn.classList.remove('bg-ink', 'text-white');
                btn.classList.add('bg-surface', 'text-ink');
                if (seatType === 'vip') {
                    btn.classList.add('bg-yellow-bg', 'text-yellow-text', 'border-yellow-text');
                    btn.classList.remove('bg-ink', 'text-white');
                }
            } else {
                selectedSeats.push({ id: seatId, number: seatNumber, type: seatType, price: price });
                btn.classList.add('bg-ink', 'text-white');
                btn.classList.remove('bg-surface', 'text-ink', 'bg-yellow-bg', 'text-yellow-text');
            }

            updateSummary();
        }

        function updateSummary() {
            const container = document.getElementById('selectedSeats');
            const seatCount = document.getElementById('seatCount');
            const totalPrice = document.getElementById('totalPrice');
            const seatIdsInput = document.getElementById('seatIdsInput');
            const submitBtn = document.getElementById('submitBtn');

            if (selectedSeats.length === 0) {
                container.innerHTML = '<span class="text-ink-secondary">Belum ada kursi dipilih</span>';
                seatCount.textContent = '0';
                totalPrice.textContent = 'Rp 0';
                seatIdsInput.value = '';
                submitBtn.disabled = true;
                return;
            }

            const labels = selectedSeats.map(s => {
                const color = s.type === 'vip' ? 'text-yellow-text' : 'text-ink';
                return `<span class="badge-blue">${s.number}</span>`;
            }).join(' ');

            container.innerHTML = labels;
            seatCount.textContent = selectedSeats.length;

            const total = selectedSeats.reduce((sum, s) => sum + s.price, 0);
            totalPrice.textContent = 'Rp ' + total.toLocaleString('id-ID');

            seatIdsInput.value = selectedSeats.map(s => s.id).join(',');
            submitBtn.disabled = false;
        }
    </script>
    @endpush
</x-app-layout>
