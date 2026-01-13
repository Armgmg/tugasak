@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        <a href="{{ route('admin.scans.index') }}" class="text-blue-400 hover:text-blue-300">← Kembali</a>

        <div class="grid grid-cols-2 gap-6">
            <!-- Image Section -->
            <div>
                <h2 class="text-xl font-bold text-white mb-4">Foto Scan</h2>
                <div class="bg-slate-800 rounded-xl overflow-hidden">
                    <img src="{{ asset($scan->image_path) }}" alt="Scan Image" class="w-full h-96 object-cover">
                </div>
            </div>

            <!-- Details Section -->
            <div class="space-y-6">
                <!-- User Info -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Informasi User</h3>
                    <div class="space-y-2 text-gray-300">
                        <p><span class="text-gray-500">Nama:</span> {{ $scan->user->name }}</p>
                        <p><span class="text-gray-500">Email:</span> {{ $scan->user->email }}</p>
                        <p><span class="text-gray-500">Waktu Scan:</span> {{ $scan->created_at->format('d M Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- AI Detection Results -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Hasil Deteksi AI</h3>
                    @if($scan->detected_items)
                        <div class="space-y-3">
                            @foreach($scan->detected_items as $item)
                                <div class="bg-slate-700 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-white">{{ $item['name'] ?? 'Item' }}</span>
                                        <span class="text-green-400">{{ round($item['confidence'] ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-600 rounded-full h-2 mt-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($item['confidence'] ?? 0) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Tidak ada data deteksi</p>
                    @endif
                </div>

                <!-- Status -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Status</h3>
                    <span class="px-4 py-2 rounded-full font-medium
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
                        @if($scan->status === 'pending' && Str::contains($scan->admin_notes, 'SYSTEM_VERIFIED'))
                            Auto Verified (Menunggu Berat)
                        @else
                            {{ ucfirst($scan->status) }}
                        @endif
                    </span>
                    @if($scan->admin_notes)
                        <p class="text-gray-400 text-sm mt-3">Catatan: {{ $scan->admin_notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Section -->
        @if($scan->status === 'pending')
            <div class="grid grid-cols-2 gap-6">
                <!-- Approve Form -->
                <form action="{{ route('admin.scans.approve', $scan) }}" method="POST"
                    class="bg-green-900/20 border border-green-500 rounded-xl p-6">
                    @csrf
                    <h3 class="text-lg font-bold text-green-400 mb-4">✓ Setujui Scan</h3>

                    @if(Str::contains($scan->admin_notes, 'SYSTEM_VERIFIED'))
                        <div class="mb-4 bg-teal-500/10 border border-teal-500/50 rounded-lg p-3">
                            <p class="text-teal-400 text-sm font-medium">🤖 Jenis sampah telah diverifikasi AI. Silakan input berat.
                            </p>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Berat Sampah (kg) / Jumlah
                                (Botol)</label>
                            <input type="number" name="weight" id="weightInput" step="0.1" min="0.1" required
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                            @error('weight')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <input type="hidden" name="poin" id="poinInput" required>
                            <div
                                class="mt-2 bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 flex justify-between items-center">
                                <span class="text-gray-300 font-medium">✨ Estimasi Poin:</span>
                                <span id="poinText" class="text-yellow-400 font-bold text-xl">0</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" rows="3"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition">
                            Setujui & Berikan Poin
                        </button>
                    </div>
                </form>

                <!-- Reject Form -->
                <form action="{{ route('admin.scans.reject', $scan) }}" method="POST"
                    class="bg-red-900/20 border border-red-500 rounded-xl p-6">
                    @csrf
                    <h3 class="text-lg font-bold text-red-400 mb-4">✗ Tolak Scan</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Alasan Penolakan</label>
                            <textarea name="reason" rows="4" required placeholder="Jelaskan mengapa scan ini ditolak..."
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-red-500 focus:outline-none"></textarea>
                            @error('reason')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition">
                            Tolak Scan
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-slate-800 rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Informasi Konfirmasi</h3>
                <div class="space-y-2 text-gray-300">
                    <p><span class="text-gray-500">Status:</span> <span
                            class="font-medium capitalize">{{ $scan->status }}</span></p>
                    @if($scan->confirmed_by)
                        <p><span class="text-gray-500">Dikonfirmasi oleh:</span> {{ $scan->confirmedBy->name }}</p>
                    @endif
                    @if($scan->total_weight)
                        <p><span class="text-gray-500">Berat Sampah:</span> {{ $scan->total_weight }} kg</p>
                    @endif
                    @if($scan->poin_earned)
                        <p><span class="text-gray-500">Poin Diberikan:</span> {{ $scan->poin_earned }}</p>
                    @endif
                    @if($scan->admin_notes)
                        <p><span class="text-gray-500">Catatan:</span> {{ $scan->admin_notes }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const weightInput = document.getElementById('weightInput');
            const poinInput = document.getElementById('poinInput');

            // Deserialize detected items from PHP
            const detectedItems = @json($scan->detected_items);

            // Determine primary type (heuristic: highest confidence or first item)
            let primaryType = 'unknown';
            if (detectedItems && detectedItems.length > 0) {
                // Check for specific keywords in item names
                const name = detectedItems[0].name.toLowerCase();
                if (name.includes('botol')) primaryType = 'botol';
                else if (name.includes('kertas') || name.includes('karton')) primaryType = 'kertas';
                else if (name.includes('logam') || name.includes('besi')) primaryType = 'logam';
                else if (name.includes('aluminium')) primaryType = 'aluminium';
            }

            console.log("Detected Primary Type for Calc:", primaryType);

            weightInput.addEventListener('input', function () {
                const val = parseFloat(this.value);
                if (isNaN(val) || val <= 0) {
                    poinInput.value = '';
                    return;
                }

                let points = 0;
                // Rates:
                // Kertas: 30 / kg
                // Botol: 10 / botol (val is treated as count)
                // Logam: 40 / kg
                // Aluminium: 30 / kg

                switch (primaryType) {
                    case 'botol':
                        points = val * 10;
                        break;
                    case 'kertas':
                        points = val * 30; // 3 per 100g = 30 per 1000g
                        break;
                    case 'logam':
                        points = val * 40;
                        break;
                    case 'aluminium':
                        points = val * 30;
                        break;
                    default:
                        // Default fallback or prompt? Let's use avg 20 or leave logic
                        points = val * 20;
                }

                poinInput.value = Math.floor(points);
                const poinText = document.getElementById('poinText');
                if (poinText) {
                    poinText.innerText = Math.floor(points);
                }
            });
        });
    </script>
@endsection