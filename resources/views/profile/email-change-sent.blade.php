<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-bold text-2xl text-ink">Verifikasi Email</h1>
    </x-slot>

    <div class="section">
        <div class="max-w-md mx-auto">
            <div class="card p-8 text-center">
                <div class="w-16 h-16 rounded-full border-1.5 border-ink bg-blue-bg text-blue-text flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="font-display font-bold text-xl text-ink mb-2">Cek Email Kamu</h2>
                <p class="text-sm text-ink-secondary mb-6">
                    Link verifikasi telah dikirim ke
                    <span class="font-medium text-ink">{{ $pendingEmail }}</span>.
                </p>

                <div class="card-sm p-4 bg-background border-dashed mb-6">
                    <p class="text-xs text-ink-secondary">
                        Buka inbox atau kotak spam kamu, lalu klik link verifikasi untuk mengubah email.
                    </p>
                </div>

                <!-- Demo: Link verifikasi (hanya untuk testing) -->
                <div class="card-sm p-4 bg-yellow-50 border-yellow-600 mb-6">
                    <p class="text-xs text-yellow-800 font-medium mb-2">Demo Link (untuk testing):</p>
                    <a href="{{ $verificationUrl }}" class="text-xs text-yellow-700 underline break-all">
                        {{ Str::limit($verificationUrl, 60) }}
                    </a>
                </div>

                <div class="flex gap-3 justify-center">
                    <a href="{{ route('profile.settings') }}" class="btn btn-outline btn-sm">
                        Kembali ke Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
