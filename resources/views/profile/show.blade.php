<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Profile</h1>
    </x-slot>

    <div class="section">
        <div class="max-w-lg mx-auto">
            <!-- Profile Card -->
            <div class="card p-8 text-center">
                <!-- Avatar -->
                <div class="mb-4">
                    @if ($user->foto)
                        <img src="{{ asset('uploads/avatars/' . $user->foto) }}" alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full border-2 border-ink object-cover mx-auto">
                    @else
                        <div class="w-24 h-24 rounded-full border-2 border-ink bg-blue-bg text-blue-text flex items-center justify-center font-display font-bold text-4xl mx-auto">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Name -->
                <h2 class="font-display font-bold text-2xl text-ink mb-1">{{ $user->name }}</h2>

                <!-- Role badge (hanya admin yang lihat) -->
                @if (auth()->user()->role === 'admin' && $user->role === 'admin')
                    <span class="badge-ink text-xs mb-2">Admin</span>
                @endif

                <!-- Bio -->
                @if ($user->bio)
                    <p class="text-sm text-ink-secondary mt-2 mb-4 max-w-xs mx-auto">{{ $user->bio }}</p>
                @else
                    @if ($user->id === auth()->id())
                        <p class="text-sm text-ink-secondary/50 mt-2 mb-4 italic">Belum ada bio. <a href="{{ route('profile.settings') }}" class="text-blue-text hover:underline">Tambahkan sekarang</a></p>
                    @else
                        <p class="text-sm text-ink-secondary/50 mt-2 mb-4 italic">Tidak ada bio</p>
                    @endif
                @endif

                <!-- Joined -->
                <p class="text-xs text-ink-secondary">Bergabung {{ $user->created_at->locale('id')->isoFormat('D MMMM Y') }}</p>

                <!-- Edit Profile Button (hanya owner) -->
                @if ($user->id === auth()->id())
                    <a href="{{ route('profile.settings') }}" class="btn btn-outline btn-sm mt-6">
                        Pengaturan Profile
                    </a>
                @endif
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['reviews'] }}</div>
                    <div class="text-xs text-ink-secondary">Review</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['bookings'] }}</div>
                    <div class="text-xs text-ink-secondary">Booking</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['watchlist'] }}</div>
                    <div class="text-xs text-ink-secondary">Watchlist</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="font-display font-bold text-2xl text-ink">{{ $stats['diary'] }}</div>
                    <div class="text-xs text-ink-secondary">Diary</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
