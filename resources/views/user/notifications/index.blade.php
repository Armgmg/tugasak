<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        <x-sidebar />

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <div
                class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-8 py-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notifikasi</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Status dan pembaruan aktivitas Anda</p>
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-auto p-8">
                <div class="max-w-4xl mx-auto">
                    <div class="flex justify-end mb-4">
                        <button onclick="markAllRead()" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                            Tandai semua dibaca
                        </button>
                    </div>

                    <div id="notificationList" class="space-y-4">
                        <!-- Loading specific -->
                        <div class="text-center py-8 text-gray-500">Memuat notifikasi...</div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadNotifications);

        async function loadNotifications() {
            try {
                const response = await fetch("{{ route('notifications.data') }}");
                const data = await response.json();
                const list = document.getElementById('notificationList');

                if (data.notifications.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="text-gray-500">Tidak ada notifikasi baru</p>
                        </div>
                    `;
                    return;
                }

                list.innerHTML = data.notifications.map(n => `
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 ${n.data.status === 'approved' ? 'border-green-500' : 'border-red-500'} flex justify-between items-start animate-fade-in">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                                ${n.data.status === 'approved' ? 'Scan Disetujui ✅' : 'Scan Ditolak ❌'}
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300">${n.data.message}</p>
                            ${n.data.points > 0 ? `<p class="text-sm font-bold text-teal-600 mt-2">+${n.data.points} Poin</p>` : ''}
                            <p class="text-xs text-gray-400 mt-3">${new Date(n.created_at).toLocaleString('id-ID')}</p>
                        </div>
                        ${!n.read_at ? '<span class="w-3 h-3 bg-red-500 rounded-full"></span>' : ''}
                    </div>
                `).join('');

            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        async function markAllRead() {
            try {
                await fetch("{{ route('notifications.readAll') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                loadNotifications();
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>

</html>