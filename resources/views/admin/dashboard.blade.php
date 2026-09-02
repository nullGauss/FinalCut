<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-display font-bold text-2xl text-ink">Dashboard Admin</h1>
            <span class="badge-ink">Admin</span>
        </div>
    </x-slot>

    <div class="section">
        <!-- Welcome Banner -->
        <div class="card p-8 mb-8">
            <p class="handwriting text-xl text-ink-secondary mb-2">Panel administrator~</p>
            <h2 class="font-display font-bold text-3xl text-ink mb-2">
                Halo, {{ Auth::user()->name }}!
            </h2>
            <p class="text-ink-secondary">
                Kelola data film, jadwal tayang, bioskop, dan pantau transaksi dari satu tempat.
            </p>
        </div>

        <!-- Quick Actions -->
        <h3 class="font-display font-bold text-lg text-ink mb-4">Kelola Data</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Kelola Film -->
            <a href="{{ route('admin.movies.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Kelola Film</h4>
                <p class="text-sm text-ink-secondary">Tambah, edit, hapus data film</p>
            </a>

            <!-- Kelola Bioskop -->
            <a href="{{ route('admin.cinemas.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Kelola Bioskop</h4>
                <p class="text-sm text-ink-secondary">Kelola bioskop & studio</p>
            </a>

            <!-- Kelola Jadwal -->
            <a href="{{ route('admin.showtimes.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Jadwal Tayang</h4>
                <p class="text-sm text-ink-secondary">Atur jadwal tayang film</p>
            </a>

            <!-- Kelola Transaksi -->
            <a href="{{ route('admin.transactions.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Transaksi</h4>
                <p class="text-sm text-ink-secondary">Pantau semua booking user</p>
            </a>

            <!-- Laporan -->
            <a href="{{ route('admin.reports.index') }}" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-blue-bg text-blue-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Laporan</h4>
                <p class="text-sm text-ink-secondary">Grafik pendapatan & export PDF</p>
            </a>

            <!-- Kelola User -->
            <a href="#" class="card p-6 hover:shadow-hard-lg transition-all group">
                <div class="w-10 h-10 bg-yellow-bg text-yellow-text rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h4 class="font-display font-bold text-ink mb-1">Kelola User</h4>
                <p class="text-sm text-ink-secondary">Ubah role & status akun</p>
            </a>
        </div>
    </div>
</x-app-layout>
