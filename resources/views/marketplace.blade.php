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
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden product-card border border-gray-200 dark:border-gray-700 flex flex-col cursor-pointer"
                                data-category="{{ $reward->category }}"
                                onclick="openDetailModal({
                                    id: {{ $reward->id }},
                                    name: '{{ addslashes($reward->name) }}',
                                    description: '{{ addslashes($reward->description) }}',
                                    category: '{{ $reward->category }}',
                                    poin_required: {{ $reward->poin_required }},
                                    image: '{{ Str::startsWith($reward->image, 'storage/') ? url($reward->image) : asset('img/' . $reward->image) }}',
                                    ranking: {{ $loop->iteration }},
                                    berat: {{ $reward->berat ?? 0 }},
                                    nilai_poin: {{ $reward->nilai_poin ?? 0 }},
                                    user_poin: {{ Auth::user()->poin }}
                                })">
                                <div class="h-48 bg-gray-200 dark:bg-gray-700 relative overflow-hidden group">
                                    <img src="{{ Str::startsWith($reward->image, 'storage/') ? url($reward->image) : asset('img/' . $reward->image) }}"
                                        alt="{{ $reward->name }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                                        onerror="this.src='{{ asset('img/placeholder.png') }}'">
                                    
                                    @if($loop->iteration == 1)
                                    <div class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 z-10 border border-yellow-300">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Peringkat #1
                                    </div>
                                    @elseif($loop->iteration == 2)
                                    <div class="absolute top-3 left-3 bg-gray-300 text-gray-800 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 z-10 border border-gray-400">
                                        <span class="font-extrabold text-sm">#2</span>
                                        Peringkat 2
                                    </div>
                                    @elseif($loop->iteration == 3)
                                    <div class="absolute top-3 left-3 bg-orange-300 text-orange-900 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 z-10 border border-orange-400">
                                        <span class="font-extrabold text-sm">#3</span>
                                        Peringkat 3
                                    </div>
                                    @endif

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
                                            onclick="event.stopPropagation(); openRedeemModal({{ $reward->id }}, '{{ addslashes($reward->name) }}', {{ $reward->poin_required }})"
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

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="detail-modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true"
            onclick="closeDetailModal()"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-[101] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                <!-- Modal Panel -->
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 w-full max-w-3xl border border-gray-200 dark:border-slate-700 mx-auto">
                    
                    <!-- Close Button -->
                    <button onclick="closeDetailModal()" 
                        class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/90 dark:bg-slate-700/90 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Modal Content -->
                    <div class="flex flex-col md:flex-row">
                        <!-- Image Section -->
                        <div class="md:w-1/2 bg-gray-100 dark:bg-slate-900 relative">
                            <div class="aspect-square relative">
                                <img id="detailImage" src="" alt="" 
                                    class="w-full h-full object-cover">
                                <!-- Ranking Badge -->
                                <div id="detailRankingBadge" class="absolute top-4 left-4 hidden"></div>
                                <!-- Points Badge -->
                                <div id="detailPointsBadge" 
                                    class="absolute top-4 right-4 bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm px-4 py-2 rounded-full font-bold text-teal-600 dark:text-teal-400 shadow-lg border border-teal-200 dark:border-teal-900">
                                </div>
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="md:w-1/2 p-6 flex flex-col">
                            <!-- Header -->
                            <div class="mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span id="detailCategory" 
                                        class="px-3 py-1 bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 text-xs font-semibold rounded-full">
                                    </span>
                                </div>
                                <h2 id="detailTitle" class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                </h2>
                                <p id="detailDescription" class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                </p>
                            </div>

                            <!-- User Points Info -->
                            <div class="mb-6 p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg border border-teal-200 dark:border-teal-900/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Poin Anda Saat Ini</p>
                                        <p id="detailUserPoints" class="text-2xl font-bold text-teal-600 dark:text-teal-400"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Poin Diperlukan</p>
                                        <p id="detailRequiredPoints" class="text-2xl font-bold text-gray-900 dark:text-white"></p>
                                    </div>
                                </div>
                                <div id="detailPointsStatus" class="mt-3 text-sm font-medium"></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto flex gap-3">
                                <button onclick="closeDetailModal()" 
                                    class="flex-1 px-4 py-3 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg transition-colors">
                                    Tutup
                                </button>
                                <button id="detailRedeemBtn" onclick="openRedeemFromDetail()" 
                                    class="flex-1 px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                    Tukar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        // Detail Modal Functions
        let currentRewardData = null;

        function openDetailModal(rewardData) {
            currentRewardData = rewardData;
            
            // Set image
            document.getElementById('detailImage').src = rewardData.image;
            document.getElementById('detailImage').alt = rewardData.name;
            
            // Set basic info
            document.getElementById('detailTitle').textContent = rewardData.name;
            document.getElementById('detailDescription').textContent = rewardData.description;
            document.getElementById('detailCategory').textContent = rewardData.category;
            
            // Set points badge
            document.getElementById('detailPointsBadge').textContent = 
                new Intl.NumberFormat('id-ID').format(rewardData.poin_required) + ' Pts';
            
            // Set ranking badge
            const rankingBadge = document.getElementById('detailRankingBadge');
            if (rewardData.ranking === 1) {
                rankingBadge.className = 'absolute top-4 left-4 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 border border-yellow-300';
                rankingBadge.innerHTML = `
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Peringkat #1
                `;
            } else if (rewardData.ranking === 2) {
                rankingBadge.className = 'absolute top-4 left-4 bg-gray-300 text-gray-800 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 border border-gray-400';
                rankingBadge.innerHTML = '<span class="font-extrabold text-sm">#2</span> Peringkat 2';
            } else if (rewardData.ranking === 3) {
                rankingBadge.className = 'absolute top-4 left-4 bg-orange-300 text-orange-900 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 border border-orange-400';
                rankingBadge.innerHTML = '<span class="font-extrabold text-sm">#3</span> Peringkat 3';
            } else {
                rankingBadge.className = 'absolute top-4 left-4 hidden';
            }
            
            // Set user points info
            document.getElementById('detailUserPoints').textContent = 
                new Intl.NumberFormat('id-ID').format(rewardData.user_poin) + ' Pts';
            document.getElementById('detailRequiredPoints').textContent = 
                new Intl.NumberFormat('id-ID').format(rewardData.poin_required) + ' Pts';
            
            // Set points status
            const statusDiv = document.getElementById('detailPointsStatus');
            const redeemBtn = document.getElementById('detailRedeemBtn');
            
            if (rewardData.user_poin >= rewardData.poin_required) {
                const remaining = rewardData.user_poin - rewardData.poin_required;
                statusDiv.className = 'mt-3 text-sm font-medium text-green-600 dark:text-green-400';
                statusDiv.innerHTML = `
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Poin Anda cukup! Sisa poin setelah penukaran: ${new Intl.NumberFormat('id-ID').format(remaining)} Pts
                `;
                redeemBtn.disabled = false;
            } else {
                const needed = rewardData.poin_required - rewardData.user_poin;
                statusDiv.className = 'mt-3 text-sm font-medium text-red-600 dark:text-red-400';
                statusDiv.innerHTML = `
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Poin Anda kurang ${new Intl.NumberFormat('id-ID').format(needed)} Pts untuk menukar reward ini
                `;
                redeemBtn.disabled = true;
            }
            
            // Show modal
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            currentRewardData = null;
        }

        function openRedeemFromDetail() {
            if (currentRewardData) {
                closeDetailModal();
                openRedeemModal(currentRewardData.id, currentRewardData.name, currentRewardData.poin_required);
            }
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