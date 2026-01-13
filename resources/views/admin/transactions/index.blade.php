<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - EcoRewards Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-8 py-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📦 Riwayat Transaksi</h2>
                    <p class="text-gray-600 dark:text-gray-400">Kelola semua transaksi penukaran poin pengguna</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                <div class="p-8 space-y-6">
                    @if(session('success'))
                        <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Transaksi</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $transactions->count() }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Menunggu Approval</p>
                            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $transactions->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Poin Diberikan</p>
                            <p class="text-3xl font-bold text-green-400 mt-2">{{ $transactions->where('status', 'approved')->sum('poin') }}</p>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">USER</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">JENIS SAMPAH</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">BERAT</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">POIN</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">TANGGAL</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">STATUS</th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($transactions as $t)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $t->user->name }}</div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400">{{ $t->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $t->jenis_sampah }}</td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $t->berat }} kg</td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-teal-600 dark:text-teal-400">{{ $t->poin }} pts</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $t->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if($t->status === 'pending')
                                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-semibold rounded-full">Menunggu</span>
                                            @elseif($t->status === 'approved')
                                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full">Disetujui</span>
                                            @else
                                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            @if($t->status === 'pending')
                                                <form action="{{ route('admin.transaksi.approve', $t->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">
                                                        ✓ Approve
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.transaksi.reject', $t->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                                        ✕ Reject
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-8 text-gray-400">
                                            Belum ada riwayat transaksi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
