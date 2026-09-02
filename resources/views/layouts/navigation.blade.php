<nav x-data="{ open: false }" class="bg-surface border-b-1.5 border-ink">
    <!-- Primary Navigation Menu -->
    <div class="section">
        <div class="flex justify-between h-16">
            <!-- Left: Logo + Links -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <span class="font-display font-bold text-xl text-ink">Final<span class="text-blue-text">Cut</span></span>
                </a>

                <!-- Nav Links -->
                <div class="hidden sm:flex items-center gap-6">
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-ink' : 'text-ink-secondary hover:text-ink' }} transition-colors">
                        Dashboard
                    </a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.movies.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.movies.*') ? 'text-ink' : 'text-ink-secondary hover:text-ink' }} transition-colors">
                            Kelola Film
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: User Dropdown -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- User name + dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center gap-2 px-4 py-2 border-1.5 border-ink rounded-full text-sm font-medium text-ink hover:bg-ink hover:text-surface transition-all">
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-48 card-sm py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-ink hover:bg-background transition-colors">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-ink hover:bg-background transition-colors">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 border-1.5 border-ink rounded-md text-ink hover:bg-ink hover:text-surface transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-ink">
        <div class="section py-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block text-sm font-medium text-ink">Dashboard</a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.movies.index') }}" class="block text-sm font-medium text-ink">Kelola Film</a>
            @endif
        </div>
        <div class="section py-4 border-t border-ink">
            <div class="text-sm font-medium text-ink mb-1">{{ Auth::user()->name }}</div>
            <div class="text-sm text-ink-secondary">{{ Auth::user()->email }}</div>
            <a href="{{ route('profile.edit') }}" class="block mt-3 text-sm text-ink-secondary hover:text-ink">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-sm text-ink-secondary hover:text-ink">Log Out</button>
            </form>
        </div>
    </div>
</nav>
