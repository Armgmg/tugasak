<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoRewards - Bank Sampah</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-sans antialiased">
    <div class="relative min-h-screen flex flex-col selection:bg-teal-500 selection:text-white">
        <!-- Navigation -->
        <header class="w-full container mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/logo.png') }}" alt="EcoRewards Logo" class="w-10 h-10 rounded-lg">
                <span class="text-xl font-bold tracking-tight">EcoRewards</span>
            </div>
            <nav class="flex gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition shadow-lg shadow-teal-500/30">
                            Menu Utama
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-teal-600 dark:hover:text-teal-400 transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition shadow-lg shadow-teal-500/30">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-6 py-12">

            <!-- Hero Slider Section -->
            <div class="relative w-full max-w-6xl mx-auto mb-20 rounded-3xl overflow-hidden shadow-2xl group"
                x-data="{ activeSlide: 0, slides: [ '{{ asset('img/slider-1.jpg') }}', '{{ asset('img/slider-2.jpg') }}', '{{ asset('img/slider-3.png') }}' ], autoPlay() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides.length }, 5000) } }"
                x-init="autoPlay()">

                <!-- Slides Container -->
                <div class="relative h-[500px] w-full">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                            x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">

                            <img :src="slide" class="w-full h-full object-cover">

                            <!-- Overlay & Text -->
                            <div
                                class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-center px-4">
                                <h1
                                    class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight animate-fade-in-up">
                                    Revolusi Daur Ulang <span class="text-teal-400">Sampah</span>
                                </h1>
                                <p class="text-lg md:text-2xl text-gray-200 mb-8 max-w-2xl animate-fade-in-up"
                                    style="animation-delay: 0.2s;">
                                    Ubah sampahmu menjadi poin berharga. Jaga lingkungan, dapatkan keuntungan.
                                </p>
                                <div class="flex gap-4 animate-fade-in-up" style="animation-delay: 0.4s;">
                                    <a href="{{ route('register') }}"
                                        class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-full transition transform hover:scale-105 shadow-lg">
                                        Mulai Sekarang
                                    </a>
                                    <a href="#layanan"
                                        class="px-8 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white font-bold rounded-full transition transform hover:scale-105">
                                        Pelajari Lebih Lanjut
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Navigation Controls -->
                <button @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
                <button @click="activeSlide = (activeSlide + 1) % slides.length"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Dots -->
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index" class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'bg-teal-500 w-8' : 'bg-white/50 hover:bg-white'">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Load Alpine.js for Slider -->
            <script src="//unpkg.com/alpinejs" defer></script>

            <!-- Layanan Section -->
            <div class="mb-20" id="layanan">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Layanan</h2>
                        <p class="text-gray-600 dark:text-gray-400">Revolusi daur ulang dari EcoRewards untuk semua
                            orang.</p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button
                            class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1: Pick Up -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-6 hover:translate-y-[-5px] transition duration-300 group border border-slate-700 hover:border-teal-500">
                        <div
                            class="w-12 h-12 bg-teal-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-teal-800">
                            <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Scan Sampah (AI Detection)</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Gunakan kamera untuk memindai sampah dan biarkan EcoReward mengenali jenisnya secara
                            otomatis. Setiap hasil scan akan dikonversi menjadi poin sebagai bentuk apresiasi atas
                            kontribusimu.
                        </p>
                    </div>

                    <!-- Card 2: Drop Off -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-6 hover:translate-y-[-5px] transition duration-300 group border border-slate-700 hover:border-blue-500">
                        <div
                            class="w-12 h-12 bg-blue-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-blue-800">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Poin & Reward</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Kumpulkan poin dari setiap aktivitas ramah lingkungan. Poin yang terkumpul bisa ditukarkan
                            dengan berbagai reward menarik dan bermanfaat.
                        </p>
                    </div>

                    <!-- Card 3: Company -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-6 hover:translate-y-[-5px] transition duration-300 group border border-slate-700 hover:border-white">
                        <div
                            class="w-12 h-12 bg-slate-700/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-slate-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Riwayat Aktivitas</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Pantau semua aktivitas pengelolaan sampahmu dalam satu tempat. Mulai dari hasil scan, jumlah
                            poin, hingga penukaran reward, semuanya tercatat dengan rapi.
                        </p>
                    </div>

                    <!-- Card 4: Event -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-6 hover:translate-y-[-5px] transition duration-300 group border border-slate-700 hover:border-red-500">
                        <div
                            class="w-12 h-12 bg-red-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-red-800">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Penukaran Reward</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Tukarkan poin EcoReward dengan hadiah pilihanmu. Proses penukaran mudah, transparan, dan
                            bisa dilakukan langsung dari aplikasi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Jenis Sampah Section -->
            <div>
                <div class="mb-8">
                    <h2 class="text-white text-3xl font-bold mt-6">
                        Jenis Sampah
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">Lihat semua jenis sampah yang kami daur ulang.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Jenis 1: Kertas -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-8 hover:translate-y-[-5px] transition duration-300 flex flex-col items-center justify-center text-center group border border-slate-700 hover:border-yellow-500 cursor-pointer">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition border-2 border-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                            <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-white">Kertas</h3>
                    </div>

                    <!-- Jenis 2: Plastik -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-8 hover:translate-y-[-5px] transition duration-300 flex flex-col items-center justify-center text-center group border border-slate-700 hover:border-green-500 cursor-pointer">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition border-2 border-green-500 shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                            <!-- Mobile Phone Icon to match reference -->
                            <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l-3 3H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-4l-3-3zm0 0V8">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-white">Plastik</h3>
                    </div>

                    <!-- Jenis 3: Aluminium -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-8 hover:translate-y-[-5px] transition duration-300 flex flex-col items-center justify-center text-center group border border-slate-700 hover:border-white cursor-pointer">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition border-2 border-white shadow-[0_0_15px_rgba(255,255,255,0.3)]">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6z M8 10h8 M8 14h8">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-white">Aluminium</h3>
                    </div>

                    <!-- Jenis 4: Besi & Logam -->
                    <div
                        class="bg-[#1e293b] rounded-2xl p-8 hover:translate-y-[-5px] transition duration-300 flex flex-col items-center justify-center text-center group border border-slate-700 hover:border-red-500 cursor-pointer">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition border-2 border-red-500 shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 12H4M20 12a1 1 0 001 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6a1 1 0 001-1M20 12a1 1 0 011-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v5M4 12a1 1 0 00-1-1V6a1 1 0 011-1h2a1 1 0 011 1v5">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-white">Besi & Logam</h3>
                    </div>


                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-20">
            <div class="container mx-auto px-6 py-10 text-center">
                <p class="text-gray-500 dark:text-gray-400">© 2026 EcoRewards. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>