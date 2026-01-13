<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center gap-3 px-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-lg">
        <div>
            <h2 class="text-xl font-bold">Admin Panel</h2>
            <p class="text-sm text-gray-400">EcoRewards</p>
        </div>
    </div>

    <!-- NAVIGATION -->
    <nav class="space-y-2">

        <!-- DASHBOARD -->
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            📊 Dashboard
        </a>

        <!-- USERS -->
        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            👥 Manajemen User
        </a>

        <!-- SCAN CONFIRMATION -->
        <a href="{{ route('admin.scans.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            📸 Konfirmasi Scan AI
        </a>



        <!-- RIWAYAT TRANSAKSI -->
        <a href="{{ route('admin.transaksi.riwayat') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            📜 Riwayat Transaksi
        </a>

        <!-- MARKETPLACE -->
        <a href="{{ route('admin.rewards.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            🎁 Kelola Marketplace
        </a>

        <!-- SPK SAW -->
        <a href="{{ route('admin.saw.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            📊 SPK Metode SAW
        </a>

        <!-- PENGATURAN -->
        <a href="{{ route('admin.settings') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
            ⚙️ Pengaturan
        </a>

    </nav>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-500/10 rounded">
            🚪 Logout
        </button>
    </form>

</div>