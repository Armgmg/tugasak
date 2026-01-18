<!-- Sidebar -->
<div class="w-64 bg-white dark:bg-gray-800 shadow-lg flex flex-col border-r border-gray-200 dark:border-gray-700 relative z-10"
    style="pointer-events: auto;">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo.png') }}" alt="EcoRewards Logo" class="w-10 h-10 rounded-lg">
            <div>
                <h1 class="font-bold text-lg text-gray-900 dark:text-white">EcoRewards</h1>
                <p class="text-xs text-gray-600 dark:text-gray-400">Bank Sampah Digital</p>
            </div>
        </div>
    </div>

    <!-- Menu Utama -->
    <div class="flex-1 overflow-y-auto p-4">
        <div class="mb-8">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 px-3">
                Menu Utama
            </h3>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg @if(request()->routeIs('dashboard')) bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 font-medium @else text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 @endif"
                    style="pointer-events: auto; display: flex; cursor: pointer;">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    <span>Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <span class="ml-auto text-xs bg-teal-600 text-white px-2 py-0.5 rounded">●</span>
                    @endif
                </a>

                <a href="{{ route('user.scan.create') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg @if(request()->routeIs('user.scan.*')) bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 font-medium @else text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 @endif"
                    style="pointer-events: auto; display: flex; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5.36 4.364l-.707.707M9 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    <span>Scan Sampah AI</span>
                    @if(request()->routeIs('user.scan.*'))
                        <span class="ml-auto text-xs bg-teal-600 text-white px-2 py-0.5 rounded">●</span>
                    @endif
                </a>

                <a href="{{ route('marketplace') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg @if(request()->routeIs('marketplace')) bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 font-medium @else text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 @endif"
                    style="pointer-events: auto; display: flex; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span>Marketplace</span>
                    @if(request()->routeIs('marketplace'))
                        <span class="ml-auto text-xs bg-teal-600 text-white px-2 py-0.5 rounded">●</span>
                    @endif
                </a>

                <a href="{{ route('riwayat-transaksi') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg @if(request()->routeIs('riwayat-transaksi')) bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 font-medium @else text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 @endif"
                    style="pointer-events: auto; display: flex; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>Riwayat Transaksi</span>
                    @if(request()->routeIs('riwayat-transaksi'))
                        <span class="ml-auto text-xs bg-teal-600 text-white px-2 py-0.5 rounded">●</span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Pengaturan -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 px-3">
                Pengaturan
            </h3>
            <nav class="space-y-2">
                <a href="{{ route('profile') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg @if(request()->routeIs('profile')) bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 font-medium @else text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 @endif"
                    style="pointer-events: auto; display: flex; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Profil Saya</span>
                    @if(request()->routeIs('profile'))
                        <span class="ml-auto text-xs bg-teal-600 text-white px-2 py-0.5 rounded">●</span>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}" style="pointer-events: auto;">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400"
                        style="cursor: pointer; pointer-events: auto;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center">
                <span class="text-lg font-bold text-white">{{ substr(Auth::user()->name ?? 'J', 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'John Doe' }}</p>
                <p class="text-xs text-teal-600 dark:text-teal-400 font-medium">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>
</div>