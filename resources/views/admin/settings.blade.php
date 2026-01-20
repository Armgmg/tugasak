@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        <h1 class="text-3xl font-bold text-white">⚙️ Pengaturan Admin</h1>

        @if ($message = Session::get('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Quick Stats -->
            <div class="bg-slate-800 rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Statistik Cepat</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Total Users</span>
                        <span class="text-white font-bold">{{ \App\Models\User::count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Scan Pending</span>
                        <span
                            class="text-yellow-400 font-bold">{{ \App\Models\Scan::where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Transaksi</span>
                        <span class="text-white font-bold">{{ \App\Models\Transaction::count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Point Configuration Removed -->

            <!-- Reward Configuration -->
            <div class="bg-slate-800 rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">🎁 Daftar Reward</h3>
                <div class="space-y-2">
                    @forelse(\App\Models\Reward::all() as $reward)
                        <div class="flex justify-between items-center p-2 bg-slate-700 rounded">
                            <span class="text-gray-300">{{ $reward->name }}</span>
                            <span class="text-yellow-400 font-bold">{{ $reward->points }} pts</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Tidak ada reward</p>
                    @endforelse
                </div>
            </div>

            <!-- App Features -->
            <div class="bg-slate-800 rounded-xl p-6 md:col-span-2">
                <h3 class="text-lg font-bold text-white mb-4">✨ Fitur Aplikasi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-3 bg-slate-700 rounded-lg">
                        <span class="text-2xl">📸</span>
                        <div>
                            <p class="font-medium text-white">Scan AI</p>
                            <p class="text-xs text-gray-400">Deteksi jenis sampah</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-700 rounded-lg">
                        <span class="text-2xl">⭐</span>
                        <div>
                            <p class="font-medium text-white">Sistem Poin</p>
                            <p class="text-xs text-gray-400">Reward digital</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-700 rounded-lg">
                        <span class="text-2xl">🏪</span>
                        <div>
                            <p class="font-medium text-white">Marketplace</p>
                            <p class="text-xs text-gray-400">Tukar poin</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-700 rounded-lg">
                        <span class="text-2xl">📊</span>
                        <div>
                            <p class="font-medium text-white">Analytics</p>
                            <p class="text-xs text-gray-400">Dashboard admin</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="bg-slate-800 rounded-xl p-6 md:col-span-2">
                <h3 class="text-lg font-bold text-white mb-4">📝 Tentang Aplikasi</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    EcoRewards adalah platform digital untuk mengelola bank sampah dengan sistem reward berbasis poin.
                    Aplikasi ini memungkinkan user untuk menyetor sampah melalui scan AI, mendapatkan poin, dan menukarnya
                    dengan reward menarik. Admin dapat mengelola user, mengonfirmasi scan, dan mengatur reward.
                </p>
                <div class="mt-4 pt-4 border-t border-slate-700">
                    <p class="text-xs text-gray-500">Last Updated: {{ now()->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection