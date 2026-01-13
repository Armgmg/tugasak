@extends('layouts.app')

@section('content')
<div class="">
    <!-- Header -->
    <div class="mb-8 bg-gradient-to-r from-teal-600 to-emerald-600 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
        {{-- Decorative SVG removed to prevent layout issues --}}
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Riwayat Transaksi</h1>
            <p class="text-teal-100/90 text-lg">Pantau dan kelola seluruh aktivitas transaksi penukaran poin.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700 overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-slate-700 bg-slate-800/50 px-6 pt-4">
            <div class="flex space-x-8">
                <button onclick="switchTab('approved')" class="tab-btn pb-4 px-2 text-sm font-semibold transition-all duration-200 border-b-2 border-transparent hover:text-teal-400 active-tab" id="tab-approved" data-target="approved">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                        Disetujui
                        <span class="bg-slate-700 text-teal-400 py-0.5 px-2 rounded-full text-xs ml-1">{{ $approved->count() }}</span>
                    </div>
                </button>
                <button onclick="switchTab('pending')" class="tab-btn pb-4 px-2 text-sm font-semibold text-slate-400 transition-all duration-200 border-b-2 border-transparent hover:text-amber-400" id="tab-pending" data-target="pending">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                        Menunggu
                        <span class="bg-slate-700 text-amber-400 py-0.5 px-2 rounded-full text-xs ml-1">{{ $pendingTransactions->count() + $pendingScans->count() }}</span>
                    </div>
                </button>
                <button onclick="switchTab('rejected')" class="tab-btn pb-4 px-2 text-sm font-semibold text-slate-400 transition-all duration-200 border-b-2 border-transparent hover:text-red-400" id="tab-rejected" data-target="rejected">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        Ditolak
                        <span class="bg-slate-700 text-red-400 py-0.5 px-2 rounded-full text-xs ml-1">{{ $rejected->count() + $rejectedScans->count() }}</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <!-- Approved Content -->
            <div id="approved-content" class="tab-content transition-opacity duration-300">
                @if($approved->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-slate-700">
                        <table class="w-full text-left">
                            <thead class="bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">Detail Sampah</th>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">Poin</th>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700 bg-slate-800">
                                @foreach($approved as $transaction)
                                    <tr class="hover:bg-slate-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-slate-700 flex items-center justify-center text-teal-400 font-bold">
                                                        {{ substr($transaction->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-white">{{ $transaction->user->name }}</div>
                                                    <div class="text-sm text-slate-400">{{ $transaction->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-200 font-medium">{{ $transaction->jenis_sampah }}</div>
                                            <div class="text-sm text-slate-500">{{ $transaction->berat }} kg</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-900/30 text-teal-400 border border-teal-900/50">
                                                +{{ number_format($transaction->poin, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                            {{ $transaction->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-900/20 text-green-400 border border-green-900/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                Selesai
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white">Belum ada transaksi</h3>
                        <p class="text-slate-400 mt-1">Belum ada transaksi yang disetujui saat ini.</p>
                    </div>
                @endif
            </div>

            <!-- Pending Content -->
            <div id="pending-content" class="tab-content hidden transition-opacity duration-300">
                @if($pendingTransactions->count() > 0 || $pendingScans->count() > 0)
                    <div class="grid gap-4">
                        {{-- PENDING SCANS --}}
                        @foreach($pendingScans as $scan)
                            <div class="bg-slate-700/30 rounded-xl border border-dashed border-amber-500/50 p-6 hover:bg-slate-700/50 transition-colors relative overflow-hidden group">
                                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-amber-900/20 flex items-center justify-center text-amber-500">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-semibold text-white">{{ $scan->user->name }}</h3>
                                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-amber-500/20 text-amber-400">SCAN AI</span>
                                                <span class="text-slate-600">•</span>
                                                <span class="text-sm text-slate-400">{{ $scan->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-4 text-sm text-slate-300">
                                                <span class="flex items-center gap-1">
                                                    Status: Menunggu Konfirmasi
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.scans.show', $scan->id) }}" class="px-4 py-2 rounded-lg bg-teal-500/10 text-teal-400 hover:bg-teal-500/20 transition-colors font-medium text-sm">
                                            Periksa & Konfirmasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- PENDING TRANSACTIONS --}}
                        @foreach($pendingTransactions as $transaction)
                            <div class="bg-slate-700/30 rounded-xl border border-slate-700 p-6 hover:bg-slate-700/50 transition-colors relative overflow-hidden group">
                                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-amber-900/20 flex items-center justify-center text-amber-500">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-semibold text-white">{{ $transaction->user->name }}</h3>
                                                <span class="text-slate-600">•</span>
                                                <span class="text-sm text-slate-400">{{ $transaction->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-4 text-sm text-slate-300">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                                    {{ $transaction->jenis_sampah }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                                                    {{ $transaction->berat }} kg
                                                </span>
                                                <span class="flex items-center gap-1 text-teal-400 font-medium">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $transaction->poin }} Poin
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.transaksi.approve', $transaction->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-green-500/10 text-green-400 hover:bg-green-500/20 transition-colors tooltip" title="Setujui">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.transaksi.reject', $transaction->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors tooltip" title="Tolak">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white">Semua Beres!</h3>
                        <p class="text-slate-400 mt-1">Tidak ada transaksi atau scan yang perlu diverifikasi.</p>
                    </div>
                @endif
            </div>

            <!-- Rejected Content -->
            <div id="rejected-content" class="tab-content hidden transition-opacity duration-300">
                @if($rejected->count() > 0 || $rejectedScans->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-slate-700">
                        <table class="w-full text-left">
                            <thead class="bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">Detail</th>
                                    <th class="px-6 py-4 text-xs font-medium text-slate-400 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700 bg-slate-800">
                                @foreach($rejectedScans as $scan)
                                    <tr class="hover:bg-slate-700/50 transition-colors opacity-75">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-slate-500 text-xs font-bold">
                                                    {{ substr($scan->user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-white">{{ $scan->user->name }}</div>
                                                    <div class="text-xs text-amber-500/80">Scan AI</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">Scan Sampah ({{ $scan->total_weight ?? 0 }}kg)</span>
                                            @if($scan->admin_notes)
                                                <div class="text-xs text-red-400 mt-1">Alasan: {{ $scan->admin_notes }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $scan->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/20 text-red-400 border border-red-900/40">
                                                Ditolak
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($rejected as $transaction)
                                    <tr class="hover:bg-slate-700/50 transition-colors opacity-75">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-slate-500 text-xs font-bold">
                                                    {{ substr($transaction->user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-white">{{ $transaction->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">{{ $transaction->jenis_sampah }} ({{ $transaction->berat }}kg)</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $transaction->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/20 text-red-400 border border-red-900/40">
                                                Ditolak
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <p class="text-slate-500">Belum ada transaksi yang ditolak.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .active-tab {
        color: #2dd4bf !important; /* teal-400 */
        border-bottom-color: #2dd4bf !important;
    }
    
    .tab-btn:hover:not(.active-tab) {
        color: #94a3b8; /* slate-400 */
    }
</style>

<script>
    function switchTab(tab) {
        // Hide all content
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Remove active state from all buttons
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active-tab');
            el.classList.add('text-slate-500');
        });

        // Show selected content
        document.getElementById(tab + '-content').classList.remove('hidden');
        
        // Add active state to clicked button
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('active-tab');
        btn.classList.remove('text-slate-500');
    }
</script>
@endsection
