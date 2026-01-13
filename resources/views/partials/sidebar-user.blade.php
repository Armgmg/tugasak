<div class="space-y-6">

    <div class="flex items-center gap-3 px-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-lg">
        <div>
            <h2 class="text-xl font-bold">EcoRewards</h2>
            <p class="text-sm text-gray-400">Bank Sampah Digital</p>
        </div>
    </div>

    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            🏠 Beranda
        </a>

        <a href="{{ route('scan-sampah') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            ♻️ Scan Sampah
        </a>

        <a href="{{ route('marketplace') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            🛒 Marketplace
        </a>

        <a href="{{ route('riwayat-transaksi') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            📜 Riwayat
        </a>

        <a href="{{ route('profile') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            👤 Profil
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-500/10 rounded">
            🚪 Logout
        </button>
    </form>

</div>