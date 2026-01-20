<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-slide-in-up {
            animation: slideInUp 0.6s ease-out;
        }

        .table-row-hover {
            transition: all 0.3s ease;
        }

        .table-row-hover:hover {
            background-color: rgba(20, 184, 166, 0.05);
        }

        .dark .table-row-hover:hover {
            background-color: rgba(20, 184, 166, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div
                class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-8 py-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola sampahmu, selamatkan bumi.</p>
                </div>
                <div class="flex items-center gap-6">
                    <div
                        class="bg-teal-50 dark:bg-teal-900/20 px-4 py-3 rounded-lg border border-teal-200 dark:border-teal-900/50">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8.16 2.75a.75.75 0 00-1.32 0l-.478 1.408A.75.75 0 006.25 5h1.5a.75.75 0 00.75-.75v-1.5zm4.08 0a.75.75 0 00-1.32 0l-.478 1.408A.75.75 0 0010.25 5h1.5a.75.75 0 00.75-.75v-1.5zM3.75 4a.75.75 0 00-.75.75v.5h13v-.5a.75.75 0 00-.75-.75h-11.5zM3 6.5v9.75A2.75 2.75 0 005.75 19h8.5A2.75 2.75 0 0017 16.25V6.5H3z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Saldo Anda</p>
                                <p class="text-lg font-bold text-teal-600 dark:text-teal-400" id="riwayatPoints">
                                    {{ number_format(Auth::user()->poin ?? 0, 0, ',', '.') }} Pts
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('notifications.index') }}"
                        class="relative p-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span id="notificationDot"
                            class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full hidden"></span>
                    </a>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Title Section -->
                    <div class="mb-8 animate-fade-in">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Riwayat Aktivitas</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Pantau status setoran sampah dan penukaran poin
                            Anda.</p>
                    </div>

                    <!-- Tabs -->
                    @php
                        $showScansByDefault = $transactions->isEmpty() && $scans->isNotEmpty();
                        $activeClass = 'px-6 py-4 font-semibold text-teal-600 dark:text-teal-400 border-b-2 border-teal-600 dark:border-teal-400 transition';
                        $inactiveClass = 'px-6 py-4 font-semibold text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 transition';
                    @endphp

                    <div class="mb-8 animate-slide-in-up">
                        <div class="flex gap-4 border-b border-gray-200 dark:border-gray-700">
                            <button onclick="switchTab('transactions')" id="tab-transactions"
                                class="{{ !$showScansByDefault ? $activeClass : $inactiveClass }}">
                                📦 Transaksi
                            </button>
                            <button onclick="switchTab('scans')" id="tab-scans"
                                class="{{ $showScansByDefault ? $activeClass : $inactiveClass }}">
                                ♻️ Scan Sampah
                            </button>
                        </div>
                    </div>

                    <!-- Filter and Export Section -->
                    <div class="mb-8 animate-slide-in-up">
                        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                            <div class="flex gap-3">
                                <button onclick="toggleFilterMenu()"
                                    class="flex items-center gap-2 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    Filter
                                </button>
                                <button onclick="exportData()"
                                    class="flex items-center gap-2 px-4 py-3 bg-gray-900 dark:bg-gray-700 text-white font-semibold rounded-lg hover:bg-gray-800 dark:hover:bg-gray-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Export
                                </button>
                            </div>

                            <!-- Filter Menu -->
                            <div id="filterMenu"
                                class="hidden absolute mt-12 right-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4 min-w-64 z-10">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg">
                                            <option>Semua Status</option>
                                            <option>Terverifikasi</option>
                                            <option>Menunggu</option>
                                            <option>Ditolak</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal
                                            Mulai</label>
                                        <input type="date"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal
                                            Akhir</label>
                                        <input type="date"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg">
                                    </div>
                                    <div class="flex gap-2 pt-2">
                                        <button
                                            class="flex-1 px-3 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">
                                            Terapkan
                                        </button>
                                        <button onclick="toggleFilterMenu()"
                                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Tab Content -->
                    <div id="transactions-content"
                        class="{{ $showScansByDefault ? 'hidden' : '' }} bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-in-up">
                        <div class="overflow-x-auto">

                            <table class="w-full">
                                <thead>
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            TRANSAKSI</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            TANGGAL</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            BERAT</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            POIN</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            STATUS</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Scans Tab Content -->
                    <div id="scans-content"
                        class="{{ $showScansByDefault ? '' : 'hidden' }} bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-in-up">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            FOTO</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            TANGGAL</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            TERDETEKSI</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            BERAT</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            POIN</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            STATUS</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="scansTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div id="transactionDetailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="transaction-detail-title"
        role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true"
            onclick="closeTransactionDetail()"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-[101] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                <!-- Modal Panel -->
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 w-full max-w-2xl border border-gray-200 dark:border-slate-700 mx-auto">

                    <!-- Close Button -->
                    <button onclick="closeTransactionDetail()"
                        class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/90 dark:bg-slate-700/90 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Modal Content -->
                    <div class="p-6">
                        <!-- Header -->
                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-3">
                                <div id="detailIcon" class="w-12 h-12 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl"></span>
                                </div>
                                <div>
                                    <h2 id="detailTransactionTitle"
                                        class="text-2xl font-bold text-gray-900 dark:text-white"></h2>
                                    <p id="detailTransactionType" class="text-sm text-gray-500 dark:text-gray-400"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="space-y-4">
                            <!-- Date -->
                            <div
                                class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal</span>
                                <span id="detailDate"
                                    class="text-sm font-semibold text-gray-900 dark:text-white"></span>
                            </div>

                            <!-- Weight -->
                            <div id="detailWeightRow"
                                class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Berat</span>
                                <span id="detailWeight"
                                    class="text-sm font-semibold text-gray-900 dark:text-white"></span>
                            </div>

                            <!-- Points -->
                            <div
                                class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Poin</span>
                                <span id="detailPoints" class="text-sm font-bold"></span>
                            </div>

                            <!-- Contact Info (for rewards) -->
                            <div id="detailContactRow"
                                class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 hidden">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Kontak</span>
                                <span id="detailContact"
                                    class="text-sm font-semibold text-gray-900 dark:text-white"></span>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</span>
                                <span id="detailStatus"
                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full">
                                    Selesai
                                </span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-6">
                            <button onclick="closeTransactionDetail()"
                                class="w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const transactionsData = @json($transactions);
        const scansData = @json($scans);

        document.addEventListener('DOMContentLoaded', () => {
            checkNotifications();
        });

        async function checkNotifications() {
            try {
                const res = await fetch("{{ route('notifications.data') }}");
                const data = await res.json();
                if (data.count > 0) {
                    const dot = document.getElementById('notificationDot');
                    if (dot) dot.classList.remove('hidden');
                } else {
                    const dot = document.getElementById('notificationDot');
                    if (dot) dot.classList.add('hidden');
                }
            } catch (e) {
                console.error('Failed to check notifications', e);
            }
        }

        function updateRiwayatPointsDisplay() {
            // Placeholder for potentially updating points dynamically
            const pointsEl = document.getElementById('riwayatPoints');
            if (pointsEl && window.authPoin) pointsEl.textContent = window.authPoin + ' Pts';
        }

        function loadTransactions() {
            const tbody = document.getElementById('transactionTableBody');
            const transactions = transactionsData || [];

            console.log('Transactions data:', transactions);

            // If no transactions, show empty message
            if (transactions.length === 0) {
                tbody.innerHTML = `
            <tr class="table-row-hover">
                <td colspan="6" class="px-6 py-8 text-center text-gray-600 dark:text-gray-400">Belum ada riwayat transaksi penukaran</td>
            </tr>`;
                return;
            }

            // Generate table rows from transactions
            // Generate table rows from transactions
            tbody.innerHTML = transactions.map(tx => {
                const isDeposit = tx.tipe_transaksi === 'setor';
                const sign = isDeposit ? '+' : '-';
                const colorClass = isDeposit ? 'text-teal-600 dark:text-teal-400' : 'text-red-600 dark:text-red-400';
                const bgClass = isDeposit ? 'bg-teal-100 dark:bg-teal-900/30' : 'bg-red-100 dark:bg-red-900/30';
                const icon = isDeposit ? '♻️' : '🎁';

                return `
            <tr class="table-row-hover">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg ${bgClass} flex items-center justify-center">
                            <span class="text-lg">${icon}</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white line-clamp-2">${tx.jenis_sampah || 'Transaksi'}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${new Date(tx.created_at).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${tx.berat ? tx.berat + ' kg' : '-'}</td>
                <td class="px-6 py-4">
                    <span class="font-bold ${colorClass}">${sign} ${tx.poin || 0} pts</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full">
                        Selesai
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <button onclick="showTransactionDetail(${tx.id})" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-semibold transition">
                        Detail
                    </button>
                </td>
            </tr>
            `;
            }).join('');
        }

        // Load and display scans
        function loadScans() {
            const tbody = document.getElementById('scansTableBody');
            const scans = scansData || [];

            console.log('Scans data:', scans);

            if (scans.length === 0) {
                tbody.innerHTML = `
                <tr class="table-row-hover">
                    <td colspan="7" class="px-6 py-8 text-center text-gray-600 dark:text-gray-400">Belum ada riwayat scan</td>
                </tr>`;
                return;
            }

            tbody.innerHTML = scans.map(scan => {
                const statusColor = scan.status === 'approved' ? 'green' : scan.status === 'rejected' ? 'red' : 'amber';
                const statusText = scan.status === 'approved' ? 'Disetujui' : scan.status === 'rejected' ? 'Ditolak' : 'Menunggu';
                let detectedItems = '-';
                try {
                    if (scan.detected_items) {
                        const items = typeof scan.detected_items === 'string' ? JSON.parse(scan.detected_items) : scan.detected_items;
                        detectedItems = Object.keys(items).join(', ');
                    }
                } catch (e) {
                    detectedItems = 'Error parsing items';
                }

                return `
    <tr class="table-row-hover">
        <td class="px-6 py-4">
            <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-200 dark:bg-slate-700">
                <img src="/storage/scans/${scan.image_path ? scan.image_path.replace('storage/app/public/scans/', '').replace('scans/', '') : 'placeholder.png'}" alt="Scan" class="w-full h-full object-cover" onerror="this.src='/img/placeholder.png'">
            </div>
        </td>
        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${new Date(scan.created_at).toLocaleDateString('id-ID')}
        </td>
        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${detectedItems}</td>
        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${scan.total_weight ? scan.total_weight + ' kg' : '-'}
        </td>
        <td class="px-6 py-4">
            <span class="font-bold text-teal-600 dark:text-teal-400">${scan.poin_earned ? '+' + scan.poin_earned :
                        '0'}</span>
        </td>
        <td class="px-6 py-4">
            <span
                class="px-3 py-1 bg-${statusColor}-100 dark:bg-${statusColor}-900/30 text-${statusColor}-700 dark:text-${statusColor}-400 text-xs font-semibold rounded-full">
                ${statusText}
            </span>
        </td>
        <td class="px-6 py-4 text-right">
            <a href="/scan/${scan.id}"
                class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-semibold transition">
                Detail
            </a>
        </td>
    </tr>
    `;
            }).join('');
        }

        // Switch between tabs
        // Switch between tabs
        function switchTab(tab) {
            const tabTx = document.getElementById('tab-transactions');
            const tabScans = document.getElementById('tab-scans');

            // Active classes
            const activeClass = 'px-6 py-4 font-semibold text-teal-600 dark:text-teal-400 border-b-2 border-teal-600 dark:border-teal-400 transition';
            // Inactive classes
            const inactiveClass = 'px-6 py-4 font-semibold text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 transition';

            if (tab === 'transactions') {
                tabTx.className = activeClass;
                tabScans.className = inactiveClass;
            } else {
                tabTx.className = inactiveClass;
                tabScans.className = activeClass;
            }

            // Update content visibility
            document.getElementById('transactions-content').classList.toggle('hidden', tab !== 'transactions');
            document.getElementById('scans-content').classList.toggle('hidden', tab !== 'scans');
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, loading data...');
            updateRiwayatPointsDisplay();
            loadTransactions();
            loadScans();

            // Auto-switch to scans if transactions empty but scans exist
            if ((!transactionsData || transactionsData.length === 0) && (scansData && scansData.length > 0)) {
                switchTab('scans');
            }
        });

        // Also call immediately in case DOM is already loaded
        if (document.readyState === 'loading') {
            // Wait for DOM
        } else {
            console.log('DOM already loaded');
            updateRiwayatPointsDisplay();
            loadTransactions();
            loadScans();

            if ((!transactionsData || transactionsData.length === 0) && (scansData && scansData.length > 0)) {
                switchTab('scans');
            }
        }

        function toggleFilterMenu() {
            const menu = document.getElementById('filterMenu');
            menu.classList.toggle('hidden');
        }

        function exportData() {
            alert('Data sedang diekspor ke format CSV...');
        }

        // Close filter menu when clicking outside
        document.addEventListener('click', function (event) {
            const filterMenu = document.getElementById('filterMenu');
            const filterBtn = event.target.closest('button');

            if (filterBtn && filterBtn.textContent.includes('Filter')) {
                return;
            }

            if (!event.target.closest('#filterMenu')) {
                filterMenu.classList.add('hidden');
            }
        });

        // Transaction Detail Modal Functions
        function showTransactionDetail(transactionId) {
            const transaction = transactionsData.find(tx => tx.id === transactionId);
            if (!transaction) {
                console.error('Transaction not found:', transactionId);
                return;
            }

            const isDeposit = transaction.tipe_transaksi === 'setor';
            const sign = isDeposit ? '+' : '-';
            const colorClass = isDeposit ? 'text-teal-600 dark:text-teal-400' : 'text-red-600 dark:text-red-400';
            const bgClass = isDeposit ? 'bg-teal-100 dark:bg-teal-900/30' : 'bg-red-100 dark:bg-red-900/30';
            const icon = isDeposit ? '♻️' : '🎁';

            // Set icon
            const iconEl = document.getElementById('detailIcon');
            iconEl.className = `w-12 h-12 rounded-lg flex items-center justify-center ${bgClass}`;
            iconEl.querySelector('span').textContent = icon;

            // Set title and type
            document.getElementById('detailTransactionTitle').textContent = transaction.jenis_sampah || 'Transaksi';
            document.getElementById('detailTransactionType').textContent = isDeposit ? 'Penukaran Sampah' : 'Penukaran Reward';

            // Set date
            document.getElementById('detailDate').textContent = new Date(transaction.created_at).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Set weight (hide if not applicable)
            const weightRow = document.getElementById('detailWeightRow');
            if (transaction.berat) {
                weightRow.classList.remove('hidden');
                document.getElementById('detailWeight').textContent = transaction.berat + ' kg';
            } else {
                weightRow.classList.add('hidden');
            }

            // Set points
            document.getElementById('detailPoints').textContent = `${sign} ${transaction.poin || 0} pts`;
            document.getElementById('detailPoints').className = `text-sm font-bold ${colorClass}`;

            // Set contact info (for rewards)
            const contactRow = document.getElementById('detailContactRow');
            if (transaction.contact_info) {
                contactRow.classList.remove('hidden');
                document.getElementById('detailContact').textContent = transaction.contact_info;
            } else {
                contactRow.classList.add('hidden');
            }

            // Show modal
            document.getElementById('transactionDetailModal').classList.remove('hidden');
        }

        function closeTransactionDetail() {
            document.getElementById('transactionDetailModal').classList.add('hidden');
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</body>

</html>