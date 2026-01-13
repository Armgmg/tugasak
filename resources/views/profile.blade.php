<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - EcoRewards</title>
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

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
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
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profil</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola informasi akun Anda.</p>
                </div>
                <div class="flex items-center gap-6">
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
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column -->
                        <div class="lg:col-span-1">
                            <!-- Profile Card -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 animate-fade-in">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center mb-4">
                                        <span class="text-4xl font-bold text-white"
                                            id="profileAvatar">{{ substr(Auth::user()->name ?? 'J', 0, 1) }}</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="profileName">
                                        {{ Auth::user()->name ?? 'John Doe' }}
                                    </h3>
                                    <p class="text-teal-600 dark:text-teal-400 font-semibold mt-1">Member Gold</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-3" id="profileEmail">{{
                                        Auth::user()->email ?? 'john@example.com' }}</p>
                                    <button onclick="toggleEditMode()"
                                        class="w-full mt-6 px-4 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition"
                                        id="editProfileBtn">
                                        Edit Profil
                                    </button>
                                </div>

                                <!-- Stats Grid -->
                                <div
                                    class="grid grid-cols-2 gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                                            {{ number_format(Auth::user()->poin ?? 0, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total Poin</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                                            {{ $transactionCount }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Transaksi</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                                            {{ $totalWeight }}kg
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Sampah</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                                            {{ $carbonSaved }}kg
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">CO₂ Hemat</p>
                                    </div>
                                </div>

                                <!-- Achievements -->
                                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Pencapaian</h4>
                                    <div class="flex gap-3">
                                        <div class="flex-1 flex justify-center">
                                            <div
                                                class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center text-xl">
                                                🌱
                                            </div>
                                        </div>
                                        <div class="flex-1 flex justify-center">
                                            <div
                                                class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-xl">
                                                ♻️
                                            </div>
                                        </div>
                                        <div class="flex-1 flex justify-center">
                                            <div
                                                class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center text-xl">
                                                🏆
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center text-xs text-gray-600 dark:text-gray-400 mt-2">
                                        <p>Pemula • Daur Ulang • Juara</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Personal Information -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 animate-slide-in-up">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Informasi Pribadi</h3>
                                <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    @if(session('success'))
                                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                                                Lengkap</label>
                                            <input type="text" id="fullName" name="name"
                                                value="{{ old('name', Auth::user()->name) }}" disabled
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                            <input type="email" id="userEmail" name="email"
                                                value="{{ old('email', Auth::user()->email) }}" disabled
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor
                                                Telepon</label>
                                            <input type="tel" id="phone" name="phone_number"
                                                value="{{ old('phone_number', Auth::user()->phone_number ?? '+62') }}"
                                                disabled
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                                            <input type="text" id="location" name="address"
                                                value="{{ old('address', Auth::user()->address ?? 'Indonesia') }}"
                                                disabled
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        </div>
                                    </div>
                                    <div id="buttonGroup" class="mt-6 flex gap-3" style="display: none;">
                                        <button type="submit"
                                            class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">
                                            Simpan Perubahan
                                        </button>
                                        <button type="button" onclick="toggleEditMode()"
                                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Account Settings -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 animate-slide-in-up">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Pengaturan Akun</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Verifikasi Email</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Email Anda sudah
                                                terverifikasi</p>
                                        </div>
                                        <span
                                            class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full">
                                            Terverifikasi
                                        </span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Two-Factor
                                                    Authentication</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Tingkatkan keamanan
                                                    akun Anda</p>
                                            </div>
                                            <button
                                                class="px-4 py-2 border border-teal-600 text-teal-600 dark:text-teal-400 font-semibold rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition">
                                                Aktifkan
                                            </button>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Notifikasi Email
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Terima update
                                                    tentang aktivitas Anda</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" checked>
                                                <div
                                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-teal-500 dark:peer-focus:ring-teal-400 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Timeline -->


                            <!-- Danger Zone -->
                            <div
                                class="bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-200 dark:border-red-900/30 p-6 animate-slide-in-up">
                                <h3 class="text-lg font-bold text-red-700 dark:text-red-400 mb-4">Zona Bahaya</h3>
                                <div class="space-y-3">
                                    <button
                                        class="w-full px-4 py-2 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 font-semibold rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                        Hapus Akun
                                    </button>
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                            Keluar dari Akun
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

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

        function toggleEditMode() {
            const fullNameInput = document.getElementById('fullName');
            const emailInput = document.getElementById('userEmail');
            const phoneInput = document.getElementById('phone');
            const locationInput = document.getElementById('location');
            const buttonGroup = document.getElementById('buttonGroup');
            const editProfileBtn = document.getElementById('editProfileBtn');

            const isDisabled = fullNameInput.disabled;

            // Toggle disabled state
            fullNameInput.disabled = !isDisabled;
            emailInput.disabled = !isDisabled;
            phoneInput.disabled = !isDisabled;
            locationInput.disabled = !isDisabled;

            // Toggle button visibility
            if (!isDisabled) {
                // Switching to view mode
                buttonGroup.style.display = 'none';
                editProfileBtn.style.display = 'block';
            } else {
                // Switching to edit mode
                buttonGroup.style.display = 'flex';
                editProfileBtn.style.display = 'none';
                fullNameInput.focus();
            }
        }

        function handleSaveProfile(event) {
            event.preventDefault();

            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('userEmail').value;
            const phone = document.getElementById('phone').value;
            const location = document.getElementById('location').value;

            // Validation
            if (!fullName.trim()) {
                alert('Nama tidak boleh kosong!');
                return;
            }

            if (!email.trim() || !email.includes('@')) {
                alert('Email tidak valid!');
                return;
            }

            if (!phone.trim()) {
                alert('Nomor telepon tidak boleh kosong!');
                return;
            }

            // Update profile card with new data
            document.getElementById('profileName').textContent = fullName;
            document.getElementById('profileEmail').textContent = email;

            // Update avatar initial
            const firstLetter = fullName.charAt(0).toUpperCase();
            document.getElementById('profileAvatar').textContent = firstLetter;

            // Update sidebar profile name too
            const sidebarNames = document.querySelectorAll('.sidebar-user-name');
            sidebarNames.forEach(el => {
                el.textContent = fullName;
            });

            // Show success message
            alert('Profil berhasil diperbarui!');

            // Switch back to view mode
            toggleEditMode();
        }
    </script>
</body>

</html>