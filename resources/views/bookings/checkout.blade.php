<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('bookings.selectSeats', $booking->showtime) }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
            <h1 class="font-display font-bold text-2xl text-ink">Checkout & Pembayaran</h1>
        </div>
    </x-slot>

    <div class="section">
        @if (session('error'))
            <div class="card-sm p-4 mb-6 bg-red-50 border-red-600 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-[1fr_400px] gap-8 items-start">
            <!-- Detail Booking -->
            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Detail Tiket</h3>
                    <div class="flex gap-6">
                        <div class="w-24 h-32 bg-background border border-gray-300 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                            @if ($booking->showtime->movie->poster)
                                <img src="{{ $booking->showtime->movie->poster }}" alt="{{ $booking->showtime->movie->title }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-ink-secondary text-center px-1">No Poster</span>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2 text-sm">
                            <h4 class="font-display font-bold text-xl text-ink">{{ $booking->showtime->movie->title }}</h4>
                            <p class="text-ink-secondary">{{ $booking->showtime->movie->durasi }} menit &middot; {{ $booking->showtime->movie->rating_umur }}</p>
                            <div class="border-t border-gray-200 pt-2 mt-2 space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-ink-secondary">Bioskop</span>
                                    <span class="font-medium text-ink">{{ $booking->showtime->studio->cinema->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ink-secondary">Studio</span>
                                    <span class="text-ink">{{ $booking->showtime->studio->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ink-secondary">Tanggal</span>
                                    <span class="text-ink">{{ $booking->showtime->show_date->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ink-secondary">Jam</span>
                                    <span class="text-ink">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kursi -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Kursi Dipilih</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($booking->seats as $seat)
                            <span class="badge-blue">
                                {{ $seat->seat_number }}
                                ({{ strtoupper($seat->seat_type) }})
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-ink-secondary mt-3">
                        {{ $booking->seats->count() }} kursi x Rp {{ number_format($booking->showtime->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Ringkasan & Pembayaran -->
            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Ringkasan Pembayaran</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ink-secondary">Harga Tiket</span>
                            <span class="text-ink">Rp {{ number_format($booking->showtime->price, 0, ',', '.') }} x {{ $booking->seats->count() }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between">
                            <span class="font-bold text-ink">Total Bayar</span>
                            <span class="font-display font-bold text-xl text-ink">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                <div class="card p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-4">Metode Pembayaran</h3>
                    <form id="payForm" method="POST" action="{{ route('bookings.pay', $booking) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-3 mb-6">
                            @php
                                $methods = [
                                    'Tunai' => 'Bayar di loket bioskop',
                                    'Kartu Kredit' => 'Visa, Mastercard, dll.',
                                    'Transfer Bank' => 'BCA, Mandiri, BRI, dll.',
                                    'E-Wallet' => 'GoPay, OVO, Dana, ShopeePay',
                                ];
                            @endphp
                            @foreach ($methods as $key => $desc)
                                <label class="flex items-center gap-3 p-3 border-1.5 border-gray-300 rounded-lg cursor-pointer hover:border-ink transition-colors has-[:checked]:border-ink has-[:checked]:bg-background">
                                    <input type="radio" name="method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}
                                           class="w-4 h-4 text-ink focus:ring-ink border-gray-300">
                                    <div>
                                        <span class="font-medium text-ink">{{ $key }}</span>
                                        <span class="text-xs text-ink-secondary block">{{ $desc }}</span>
                                    </div>
                                </label>
                            @endforeach
                            @error('method')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="button" onclick="openPayModal()" class="btn btn-primary w-full justify-center">
                            Bayar Sekarang
                        </button>
                    </form>

                    <button type="button" onclick="openCancelModal()" class="btn btn-outline w-full justify-center text-sm mt-3">
                        Batalkan Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Bayar -->
    <div id="payModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closePayModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Konfirmasi Pembayaran</h3>
                <p class="text-ink-secondary text-sm mb-1">Metode: <span class="font-medium text-ink" id="payMethod">-</span></p>
                <p class="text-ink-secondary text-sm mb-4">Total yang harus dibayar:</p>
                <p class="font-display font-bold text-2xl text-ink mb-6">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                <div class="flex gap-3">
                    <button onclick="closePayModal()" class="btn btn-outline btn-sm flex-1">Batal</button>
                    <button onclick="submitPay()" class="btn btn-primary btn-sm flex-1">Bayar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Batal -->
    <div id="cancelModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-ink/50" onclick="closeCancelModal()"></div>
        <div class="absolute inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-full sm:max-w-sm bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">Batalkan Booking?</h3>
                <p class="text-ink-secondary text-sm mb-6">Kursi yang dipilih akan tersedia kembali untuk dipesan orang lain.</p>
                <div class="flex gap-3">
                    <button onclick="closeCancelModal()" class="btn btn-outline btn-sm flex-1">Kembali</button>
                    <form id="cancelForm" method="POST" action="{{ route('bookings.cancel', $booking) }}" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm w-full justify-center border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openPayModal() {
            const method = document.querySelector('input[name="method"]:checked');
            document.getElementById('payMethod').textContent = method ? method.value : '-';
            document.getElementById('payModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePayModal() {
            document.getElementById('payModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function submitPay() {
            closePayModal();
            document.getElementById('payForm').submit();
        }

        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>
