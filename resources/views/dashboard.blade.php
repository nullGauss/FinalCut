<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Dashboard</h1>
            <span class="badge-blue">User</span>
        </div>
    </x-slot>

    <div class="section">
        <!-- Welcome Banner -->
        <div class="card p-8 mb-8">
            <p class="handwriting text-xl text-ink-secondary mb-2">Selamat datang di FinalCut~</p>
            <h2 class="font-display font-bold text-3xl text-ink mb-2">
                Halo, {{ Auth::user()->name }}!
            </h2>
            <p class="text-ink-secondary">
                Mau nonton apa hari ini? Cari film favoritmu, baca ulasan dari komunitas, atau langsung booking tiket bioskop.
            </p>
        </div>

        <!-- Quick Actions -->
        <h3 class="font-display font-bold text-lg text-ink mb-4">Menu Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Browse Film -->
            <a href="{{ route('movies.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Browse Film</h4>
                <p class="text-sm text-ink-secondary">Cari & lihat detail film favoritmu</p>
            </a>

            <!-- My Watchlist -->
            <a href="{{ route('collection.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Watchlist</h4>
                <p class="text-sm text-ink-secondary">Film yang mau kamu tonton</p>
            </a>

            <!-- Booking -->
            <a href="{{ route('movies.nowShowing') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Booking Tiket</h4>
                <p class="text-sm text-ink-secondary">Pesan tiket bioskop sekarang</p>
            </a>
        </div>
    </div>
</x-app-layout>
