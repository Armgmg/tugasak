<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - EcoRewards</title>
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

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tukar Poin</h2>
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
                                <p class="text-lg font-bold text-teal-600 dark:text-teal-400" id="marketplacePoints">
                                    {{ Auth::check() ? Auth::user()->poin : 0 }} Pts
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
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Tukar Poin</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Tukarkan poin hasil daur ulangmu dengan hadiah
                            menarik.</p>
                    </div>

                    <!-- Search and Filter -->
                    <div class="mb-8 animate-slide-in-up">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <!-- Search -->
                            <div class="flex-1 relative">
                                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" placeholder="Cari hadiah..."
                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>

                            <!-- Category Filter -->
                            <div class="flex gap-2 flex-wrap md:flex-nowrap overflow-x-auto pb-2 md:pb-0">
                                <button onclick="filterProducts('all')"
                                    class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition whitespace-nowrap category-btn active"
                                    data-category="all">
                                    Semua
                                </button>
                                <button onclick="filterProducts('Voucher')"
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn"
                                    data-category="Voucher">
                                    Voucher
                                </button>
                                <button onclick="filterProducts('Barang')"
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn"
                                    data-category="Barang">
                                    Barang
                                </button>
                                <button onclick="filterProducts('Sembako')"
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn"
                                    data-category="Sembako">
                                    Sembako
                                </button>
                                <button onclick="filterProducts('Pulsa/Token')"
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn"
                                    data-category="Pulsa/Token">
                                    Pulsa/Token
                                </button>
                                <button onclick="filterProducts('Lainnya')"
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn"
                                    data-category="Lainnya">
                                    Lainnya
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Grid Produk -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-slide-in-up">
                        @forelse($rewards as $reward)
                            <!-- Product Card -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden product-card border border-gray-200 dark:border-gray-700 flex flex-col"
                                data-category="{{ $reward->category }}">
                                <div class="h-48 bg-gray-200 dark:bg-gray-700 relative overflow-hidden group">
                                    <img src="{{ Str::startsWith($reward->image, 'storage/') ? url($reward->image) : asset('img/' . $reward->image) }}"
                                        alt="{{ $reward->name }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                                        onerror="this.src='{{ asset('img/placeholder.png') }}'">
                                    <div
                                        class="absolute top-3 right-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-teal-600 dark:text-teal-400 shadow-sm">
                                        {{ number_format($reward->poin_required, 0, ',', '.') }} Pts
                                    </div>
                                </div>
                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-1">{{ $reward->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {{ $reward->description }}
                                        </p>
                                    </div>
                                    <div
                                        class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Stok: Tersedia</span>
                                        <button
                                            onclick="openRedeemModal({{ $reward->id }}, '{{ addslashes($reward->name) }}', {{ $reward->poin_required }})"
                                            class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ Auth::user()->poin < $reward->poin_required ? 'disabled' : '' }}>
                                            Tukar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p class="text-xl font-semibold">Belum ada reward yang tersedia.</p>
                                <p class="text-sm mt-2">Cek kembali nanti untuk penawaran menarik!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Redemption Modal (Modern Flex Layout) -->
    <div id="redeemModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true"
            onclick="closeRedeemModal()"></div>

        <!-- Modal Container (Flex Center) -->
        <div class="fixed inset-0 z-[101] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                <!-- Modal Panel -->
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg border border-gray-200 dark:border-slate-700 mx-auto">
                    <form action="{{ route('marketplace.tukar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reward_id" id="modalRewardId">

                        <!-- Header -->
                        <div class="bg-white dark:bg-slate-800 px-6 pt-6 pb-4">
                            <div class="flex flex-col items-center sm:flex-row sm:items-start text-center sm:text-left">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 dark:bg-teal-900/50 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-teal-600 dark:text-teal-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                    </svg>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-4 flex-1">
                                    <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"
                                        id="modalTitle">
                                        Tukar Poin
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" id="modalDescription">
                                        Informasi penukaran.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900/50">
                            <label for="contact_info"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor HP / Email <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="contact_info" id="contact_info" required
                                placeholder="08123456789 atau email@example.com"
                                class="w-full px-4 py-3 rounded-lg bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-black placeholder-gray-400 focus:ring-2 focus:ring-teal-500 outline-none transition-all">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Info ini digunakan untuk konfirmasi pengiriman reward.
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-100 dark:bg-slate-800/80 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-teal-600 text-base font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                                Konfirmasi
                            </button>
                            <button type="button" onclick="closeRedeemModal()"
                                class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-gray-300 dark:border-slate-600 shadow-sm px-5 py-2.5 bg-white dark:bg-slate-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        function openRedeemModal(id, name, points) {
            document.getElementById('modalRewardId').value = id;
            document.getElementById('modalTitle').textContent = 'Tukar ' + name;
            document.getElementById('modalDescription').textContent = 'Poin yang diperlukan: ' + points + ' Pts. Silakan masukkan kontak yang bisa dihubungi.';
            document.getElementById('redeemModal').classList.remove('hidden');
        }

        function closeRedeemModal() {
            document.getElementById('redeemModal').classList.add('hidden');
        }

        function filterProducts(category) {
            // Update active button state
            document.querySelectorAll('.category-btn').forEach(btn => {
                // Classes for active state
                const activeClasses = ['bg-teal-600', 'text-white', 'hover:bg-teal-700', 'border-transparent'];
                // Classes for inactive state
                const inactiveClasses = ['bg-white', 'dark:bg-transparent', 'border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700'];

                if (btn.dataset.category === category) {
                    btn.classList.add(...activeClasses);
                    btn.classList.remove(...inactiveClasses);
                } else {
                    btn.classList.remove(...activeClasses);
                    btn.classList.add(...inactiveClasses);
                }
            });

            // Filter items
            const products = document.querySelectorAll('.product-card');
            products.forEach(product => {
                // Case insensitive comparison for robustness
                const prodCat = product.dataset.category.toLowerCase();
                const targetCat = category.toLowerCase();

                if (targetCat === 'all' || prodCat === targetCat) {
                    product.classList.remove('hidden');
                } else {
                    product.classList.add('hidden');
                }
            });
        }
    </script>
</body>

</html>