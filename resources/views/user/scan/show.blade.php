<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Scan - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Scan Sampah</h2>
                    <p class="text-gray-600 dark:text-gray-400">Lihat status dan detail scan sampahmu</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                <div class="p-8 space-y-6">
                    @if ($message = Session::get('success'))
                        <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                            {{ $message }}
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Image -->
                        <div>
                            <h2 class="text-xl font-bold text-white mb-4">Foto Scan</h2>
                            <div class="bg-slate-800 rounded-xl overflow-hidden">
                                <img src="{{ asset('storage/' . $scan->image_path) }}" alt="Scan Image"
                                    class="w-full h-96 object-cover">
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="space-y-6">
                            <!-- Status Card -->
                            <div class="bg-slate-800 rounded-xl p-6">
                                <h3 class="text-lg font-bold text-white mb-4">📊 Status Scan</h3>
                                <div class="mb-4">
                                    <span class="px-4 py-2 rounded-full font-bold text-lg
                        @if($scan->status === 'pending')
                            @if(Str::contains($scan->admin_notes, 'SYSTEM_VERIFIED'))
                                bg-teal-500/20 text-teal-400
                            @else
                                bg-yellow-500/20 text-yellow-400
                            @endif
                        @elseif($scan->status === 'approved')
                            bg-green-500/20 text-green-400
                        @else
                            bg-red-500/20 text-red-400
                        @endif">
                                        @if($scan->status === 'pending')
                                            @if(Str::contains($scan->admin_notes, 'SYSTEM_VERIFIED'))
                                                🤖 Terverifikasi (Menunggu Penimbangan)
                                            @else
                                                ⏳ Menunggu Konfirmasi
                                            @endif
                                        @elseif($scan->status === 'approved')
                                            ✅ Disetujui
                                        @else
                                            ❌ Ditolak
                                        @endif
                                    </span>
                                </div>
                                <p class="text-gray-400 text-sm">Waktu Upload:
                                    {{ $scan->created_at->format('d M Y H:i:s') }}
                                </p>
                            </div>

                            <!-- Detected Items -->
                            @if($scan->detected_items && count($scan->detected_items) > 0)
                                <div class="bg-slate-800 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-white mb-4">🎯 Hasil Deteksi AI</h3>
                                    <div class="space-y-3">
                                        @foreach($scan->detected_items as $item)
                                            <div class="bg-slate-700 rounded-lg p-3">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="font-medium text-white">{{ $item['name'] ?? 'Item' }}</span>
                                                    <span
                                                        class="text-green-400 font-bold">{{ round($item['confidence'] ?? 0, 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-slate-600 rounded-full h-2">
                                                    <div class="bg-green-500 h-2 rounded-full"
                                                        style="width: {{ ($item['confidence'] ?? 0) }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Confirmation Details -->
                            @if($scan->status !== 'pending')
                                <div class="bg-slate-800 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-white mb-4">
                                        @if($scan->status === 'approved')
                                            ✅ Detail Persetujuan
                                        @else
                                            ⚠️ Alasan Penolakan
                                        @endif
                                    </h3>
                                    <div class="space-y-3 text-gray-300">
                                        @if($scan->confirmed_by)
                                            <p>
                                                <span class="text-gray-500">Dikonfirmasi oleh:</span>
                                                <strong>{{ $scan->confirmedBy->name }}</strong>
                                            </p>
                                        @endif

                                        @if($scan->total_weight)
                                            <p>
                                                <span class="text-gray-500">Berat Sampah:</span>
                                                <strong>{{ $scan->total_weight }} kg</strong>
                                            </p>
                                        @endif

                                        @if($scan->poin_earned)
                                            <p class="flex items-center gap-2">
                                                <span class="text-gray-500">Poin Diterima:</span>
                                                <span class="text-yellow-400 font-bold text-lg">+{{ $scan->poin_earned }}
                                                    🎯</span>
                                            </p>
                                        @endif

                                        @if($scan->admin_notes)
                                            <div class="mt-4 pt-4 border-t border-slate-700">
                                                <p class="text-gray-500 text-sm mb-2">Catatan:</p>
                                                <p class="text-gray-300">{{ $scan->admin_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Info Box -->
                                @if($scan->status === 'approved')
                                    <div class="bg-green-900/20 border border-green-500 rounded-lg p-4">
                                        <p class="text-green-300 text-sm">
                                            🎉 Scan kamu telah disetujui! Poin sudah masuk ke akun kamu.
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-white border border-red-500 rounded-lg p-4">
                                        <p class="text-Black text-sm">
                                            <b>Scan ini ditolak. Silakan coba lagi dengan foto yang lebih jelas.</b>
                                        </p>
                                    </div>
                                @endif
                            @else
                                @if(Str::contains($scan->admin_notes, 'SYSTEM_VERIFIED'))
                                    <div class="bg-white border border-red rounded-lg p-4">
                                        <p class="text-black text-sm">
                                            <b>🤖 Jenis sampah telah diverifikasi otomatis oleh sistem. Menunggu admin untuk
                                                menimbang berat sampah untuk menghitung poin final.</b>
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-yellow-900/20 border border-yellow-500 rounded-lg p-4">
                                        <p class="text-yellow-300 text-sm">
                                            ⏳ Admin masih mereview scan ini. Tunggu konfirmasi dalam waktu 1-24 jam.
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <a href="{{ route('user.scan.create') }}"
                            class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                            ← Kembali
                        </a>
                        @if($scan->status === 'pending' || $scan->status === 'rejected')
                            <a href="{{ route('user.scan.create') }}"
                                class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
                                Scan Lagi →
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>