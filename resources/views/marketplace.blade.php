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
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-8 py-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tukar Poin</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola sampahmu, selamatkan bumi.</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="bg-teal-50 dark:bg-teal-900/20 px-4 py-3 rounded-lg border border-teal-200 dark:border-teal-900/50">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 2.75a.75.75 0 00-1.32 0l-.478 1.408A.75.75 0 006.25 5h1.5a.75.75 0 00.75-.75v-1.5zm4.08 0a.75.75 0 00-1.32 0l-.478 1.408A.75.75 0 0010.25 5h1.5a.75.75 0 00.75-.75v-1.5zM3.75 4a.75.75 0 00-.75.75v.5h13v-.5a.75.75 0 00-.75-.75h-11.5zM3 6.5v9.75A2.75 2.75 0 005.75 19h8.5A2.75 2.75 0 0017 16.25V6.5H3z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Saldo Anda</p>
                                <p class="text-lg font-bold text-teal-600 dark:text-teal-400" id="marketplacePoints">2450 Pts</p>
                            </div>
                        </div>
                    </div>
                    <button class="relative p-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Title Section -->
                    <div class="mb-8 animate-fade-in">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Tukar Poin</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Tukarkan poin hasil daur ulangmu dengan hadiah menarik.</p>
                    </div>

                    <!-- Search and Filter -->
                    <div class="mb-8 animate-slide-in-up">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <!-- Search -->
                            <div class="flex-1 relative">
                                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" placeholder="Cari hadiah..." class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>

                            <!-- Category Filter -->
                            <div class="flex gap-2 flex-wrap md:flex-nowrap">
                                <button onclick="filterProducts('all')" class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition whitespace-nowrap category-btn active" data-category="all">
                                    Semua
                                </button>
                                <button onclick="filterProducts('voucher')" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn" data-category="voucher">
                                    Voucher
                                </button>
                                <button onclick="filterProducts('product')" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn" data-category="product">
                                    Product
                                </button>
                                <button onclick="filterProducts('donation')" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap category-btn" data-category="donation">
                                    Donation
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Current points - initialize from localStorage or default value
        let currentPoints = parseInt(localStorage.getItem('userPoints')) || 2450;
        
        // Update marketplace header display on page load
        function updateMarketplaceDisplay() {
            const formattedPoints = currentPoints.toLocaleString('id-ID');
            const marketplacePointsEl = document.getElementById('marketplacePoints');
            if (marketplacePointsEl) {
                marketplacePointsEl.textContent = formattedPoints + ' Pts';
            }
        }
        
        // Update on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', updateMarketplaceDisplay);
        updateMarketplaceDisplay();

        function filterProducts(category) {
            const cards = document.querySelectorAll('.product-card');
            const buttons = document.querySelectorAll('.category-btn');

            // Update button styles
            buttons.forEach(btn => {
                if (btn.getAttribute('data-category') === category) {
                    btn.classList.add('bg-teal-600', 'text-white');
                    btn.classList.remove('border', 'border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                } else {
                    btn.classList.remove('bg-teal-600', 'text-white');
                    btn.classList.add('border', 'border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                }
            });

            // Filter cards
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function tukarPoin(points, productName) {
            // Check if user has enough points
            if (currentPoints < points) {
                alert(`Poin Anda tidak cukup!\n\nPoin yang diperlukan: ${points} Pts\nPoin Anda: ${currentPoints} Pts`);
                return;
            }

            // Confirm transaction
            const confirmed = confirm(`Tukar ${productName}?\n\nPoin yang akan digunakan: ${points} Pts\nPoin tersisa: ${currentPoints - points} Pts`);

            if (confirmed) {
                // Show loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loadingOverlay';
                loadingOverlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                `;
                loadingOverlay.innerHTML = `
                    <div style="background: white; padding: 40px; border-radius: 12px; text-align: center;">
                        <div style="width: 50px; height: 50px; border: 4px solid #f3f4f6; border-top: 4px solid #14b8a6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                        <p style="color: #374151; font-size: 16px; font-weight: 600;">Memproses transaksi...</p>
                    </div>
                    <style>
                        @keyframes spin {
                            to { transform: rotate(360deg); }
                        }
                    </style>
                `;
                document.body.appendChild(loadingOverlay);

                // Deduct points
                currentPoints -= points;

                // Save to localStorage
                localStorage.setItem('userPoints', currentPoints);

                // Add transaction to history
                const transaction = {
                    id: Date.now(),
                    type: 'tukar',
                    name: productName,
                    points: points,
                    date: new Date().toLocaleString('id-ID'),
                    status: 'Selesai'
                };
                
                let transactions = JSON.parse(localStorage.getItem('transactionHistory')) || [];
                transactions.unshift(transaction);
                localStorage.setItem('transactionHistory', JSON.stringify(transactions));

                // Update marketplace display immediately
                updateMarketplaceDisplay();

                // Show success message with new points
                alert(`Berhasil menukar ${productName}!\n\nPoin Anda sekarang: ${currentPoints} Pts`);

                // Remove loading overlay before redirect
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.remove();

                // Redirect to dashboard to show updated points
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 500);
            }
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
