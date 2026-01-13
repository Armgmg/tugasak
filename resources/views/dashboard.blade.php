<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Beranda</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola sampahmu, jaga bumi.</p>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ url('/') }}"
                        class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>Home</span>
                    </a>
                    <div
                        class="flex items-center gap-2 bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/20 dark:to-amber-900/20 px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <span class="font-bold text-gray-900 dark:text-white"
                            id="dashboardPoints">{{ number_format(Auth::user()->poin ?? 0, 0, ',', '.') }} Poin</span>
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
                    <!-- Welcome Section -->
                    <div class="flex items-center justify-between mb-8 animate-fade-in">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Hallo,
                                {{ Auth::user()->name }}! 👋
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Siap untuk berkontribusi pada lingkungan
                                hari ini?</p>
                        </div>
                        <button onclick="window.location.href='{{ route('user.scan.create') }}'"
                            class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12 2a1 1 0 01.894.553l1.348 2.696a1 1 0 00.894.553h2.988a1 1 0 01.894 1.447l-2.411 1.754a1 1 0 00-.365 1.118l.868 2.693a1 1 0 01-1.53 1.122L12 9.69l-2.686 1.952a1 1 0 01-1.53-1.122l.868-2.693a1 1 0 00-.365-1.118L5.276 8.249a1 1 0 01.894-1.447h2.988a1 1 0 00.894-.553l1.348-2.696a1 1 0 01.894-.553z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Setor Sampah Sekarang
                        </button>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 animate-slide-in-up">
                        <!-- Stat 1 -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-teal-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Poin Saya</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><span
                                            id="statPoints">{{ number_format(Auth::user()->poin ?? 0, 0, ',', '.') }}</span>
                                        <span class="text-lg text-gray-500">pts</span>
                                    </p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M8.16 2.75a.75.75 0 00-1.32 0l-.478 1.408A.75.75 0 006.25 5h1.5a.75.75 0 00.75-.75v-1.5zm4.08 0a.75.75 0 00-1.32 0l-.478 1.408A.75.75 0 0010.25 5h1.5a.75.75 0 00.75-.75v-1.5zM3.75 4a.75.75 0 00-.75.75v.5h13v-.5a.75.75 0 00-.75-.75h-11.5zM3 6.5v9.75A2.75 2.75 0 005.75 19h8.5A2.75 2.75 0 0017 16.25V6.5H3z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Stat 2 -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Berat Sampah
                                    </p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                        {{ number_format($totalWeight, 1, ',', '.') }} <span
                                            class="text-lg text-gray-500">kg</span>
                                    </p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z">
                                        </path>
                                        <path
                                            d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Chart and Recent Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Activity Analysis Chart -->
                        <div
                            class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 animate-slide-in-up">
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Analisis Aktivitas</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Perolehan poin dalam 7 hari
                                    terakhir</p>
                            </div>
                            <div style="position: relative; height: 300px;">
                                <canvas id="activityChart"></canvas>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 animate-slide-in-up">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Terbaru</h3>
                                <a href="{{ route('riwayat-transaksi') }}"
                                    class="text-teal-600 dark:text-teal-400 text-sm font-medium hover:underline">Lihat
                                    Semua</a>
                            </div>
                            <div class="flex flex-col">
                                @forelse($recentTransactions as $transaction)
                                    <div
                                        class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/30 px-2 -mx-2 rounded-md transition group">
                                        <div class="flex-1 min-w-0 flex items-center gap-3 pr-4">
                                            <div
                                                class="w-9 h-9 rounded-full flex items-center justify-center {{ $transaction->display_poin > 0 ? 'bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400' : 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' }}">
                                                @if (isset($transaction->type) && $transaction->type === 'scan')
                                                    <!-- Scan Icon -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                @elseif ($transaction->display_poin > 0)
                                                    <!-- Plus Icon -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                @else
                                                    <!-- Minus Icon -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 12H4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition truncate">
                                                    {{ $transaction->description }}
                                                </p>
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                                    {{ $transaction->created_at->locale('id')->isoFormat('D MMM Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm font-bold whitespace-nowrap flex-shrink-0 {{ $transaction->display_poin > 0 ? 'text-teal-600 dark:text-teal-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $transaction->display_poin > 0 ? '+' : '' }}{{ number_format($transaction->display_poin, 0, ',', '.') }}
                                            Pts
                                        </span>
                                    </div>
                                @empty
                                    <div
                                        class="flex flex-col items-center justify-center py-8 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm">Belum ada aktivitas terbaru</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Chart.js Activity Chart
        const ctx = document.getElementById('activityChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
        gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Poin',
                    data: @json($chartData),
                    borderColor: 'rgb(13, 148, 136)',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(13, 148, 136)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgb(107, 114, 128)',
                            font: {
                                size: 12,
                            }
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            color: 'rgb(107, 114, 128)',
                            font: {
                                size: 12,
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });

        // Check notifications
        document.addEventListener('DOMContentLoaded', checkNotifications);

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
                return data;
            } catch (e) {
                console.error('Failed to check notifications', e);
                return null;
            }
        }

        async function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');

            if (!dropdown.classList.contains('hidden')) {
                const list = document.getElementById('dropdownList');
                list.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Memuat...</div>';

                const data = await checkNotifications();
                if (data && data.notifications.length > 0) {
                    list.innerHTML = data.notifications.map(n => `
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                            <div class="flex items-start gap-3">
                                <div class="${n.data.status === 'approved' ? 'text-green-500' : 'text-red-500'} mt-1">
                                    ${n.data.status === 'approved' ? '✅' : '❌'}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800 dark:text-gray-200">${n.data.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${new Date(n.created_at).toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi baru</div>';
                }
            }
        }

        async function markAllRead() {
            // Need csrf token
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.content : '';

            await fetch("{{ route('notifications.readAll') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token }
            });
            checkNotifications();
            document.getElementById('dropdownList').innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi baru</div>';
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            width: 8px;
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