<footer class="border-t border-gray-200 bg-surface mt-8">
    <div class="max-w-5xl mx-auto px-6 py-5">
        <!-- Top Row: Links + Social -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-3">
            <!-- Links -->
            <nav class="flex items-center gap-4 flex-wrap justify-center text-xs text-ink-secondary">
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-ink transition-colors">Dashboard</a>
                    <a href="{{ route('admin.movies.index') }}" class="hover:text-ink transition-colors">Kelola Film</a>
                    <a href="{{ route('admin.cinemas.index') }}" class="hover:text-ink transition-colors">Kelola Bioskop</a>
                    <a href="{{ route('admin.reports.index') }}" class="hover:text-ink transition-colors">Laporan</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-ink transition-colors">Logout</button>
                    </form>
                @elseif (auth()->check())
                    <a href="{{ route('dashboard') }}" class="hover:text-ink transition-colors">Dashboard</a>
                    <a href="{{ route('movies.index') }}" class="hover:text-ink transition-colors">Browse Film</a>
                    <a href="{{ route('movies.nowShowing') }}" class="hover:text-ink transition-colors">Booking</a>
                    <a href="{{ route('collection.index') }}" class="hover:text-ink transition-colors">Koleksi</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-ink transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="hover:text-ink transition-colors">Daftar</a>
                    <a href="{{ route('login') }}" class="hover:text-ink transition-colors">Login</a>
                @endguest
            </nav>

            <!-- Social Icons -->
            <div class="flex items-center gap-2.5">
                <!-- Instagram -->
                <a href="https://www.instagram.com/yogurtleomord/" target="_blank" rel="noopener noreferrer"
                   class="w-7 h-7 rounded-full border border-ink/30 flex items-center justify-center text-ink-secondary hover:text-ink hover:border-ink transition-all"
                   title="Instagram">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                <!-- YouTube -->
                <a href="https://www.youtube.com/@YogurtLeomord" target="_blank" rel="noopener noreferrer"
                   class="w-7 h-7 rounded-full border border-ink/30 flex items-center justify-center text-ink-secondary hover:text-ink hover:border-ink transition-all"
                   title="YouTube">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>
                <!-- X / Twitter -->
                <a href="https://x.com/YogurtLeomord" target="_blank" rel="noopener noreferrer"
                   class="w-7 h-7 rounded-full border border-ink/30 flex items-center justify-center text-ink-secondary hover:text-ink hover:border-ink transition-all"
                   title="X / Twitter">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <!-- WhatsApp -->
                <a href="https://wa.me/6285643747756" target="_blank" rel="noopener noreferrer"
                   class="w-7 h-7 rounded-full border border-ink/30 flex items-center justify-center text-ink-secondary hover:text-green-600 hover:border-green-600 transition-all"
                   title="WhatsApp">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
                <!-- Email -->
                <button type="button" onclick="openEmailModal()"
                   class="w-7 h-7 rounded-full border border-ink/30 flex items-center justify-center text-ink-secondary hover:text-blue-text hover:border-blue-text transition-all cursor-pointer"
                   title="Email">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Bottom Row: Copyright -->
        <div class="border-t border-gray-200 pt-3 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-[11px] text-ink-secondary">&copy; {{ date('Y') }} FinalCut. Hak Cipta Dilindungi.</p>
            <div class="flex items-center gap-3 text-[11px] text-ink-secondary">
                <a href="#" class="hover:text-ink transition-colors">Kebijakan Privasi</a>
                <span class="text-gray-300">/</span>
                <a href="#" class="hover:text-ink transition-colors">Syarat & Ketentuan</a>
                <span class="text-gray-300">/</span>
                <a href="#" class="hover:text-ink transition-colors">Disclaimer</a>
            </div>
        </div>
    </div>
</footer>

<!-- Email Modal -->
<div id="emailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-ink/40" onclick="closeEmailModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-surface border-1.5 border-ink shadow-hard-lg rounded-xl w-full max-w-sm overflow-hidden relative">
            <!-- Header -->
            <div class="bg-ink text-surface px-5 py-3 flex items-center justify-between">
                <span class="font-display font-bold text-sm">Email Me</span>
                <button onclick="closeEmailModal()" class="text-surface/70 hover:text-surface transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Body -->
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full border-1.5 border-ink bg-blue-bg text-blue-text flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="handwriting text-ink-secondary mb-2">Kirim email ke~</p>
                <a href="mailto:tubagusrestu26@gmail.com" class="font-display font-bold text-ink text-lg hover:text-blue-text hover:underline transition-colors">
                    tubagusrestu26@gmail.com
                </a>
            </div>
            <!-- Footer -->
            <div class="border-t border-gray-200 px-5 py-3 flex justify-center">
                <button onclick="closeEmailModal()" class="btn btn-outline btn-sm text-xs">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEmailModal() {
    document.getElementById('emailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEmailModal() {
    document.getElementById('emailModal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeEmailModal();
});
</script>
