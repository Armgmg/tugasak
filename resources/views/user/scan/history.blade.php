@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-white">📋 Riwayat Scan</h1>
        <a href="{{ route('user.scan.create') }}" class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
            + Scan Baru
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-slate-800 rounded-lg p-4 border-l-4 border-yellow-500">
            <p class="text-gray-400 text-sm">Menunggu Konfirmasi</p>
            <p class="text-2xl font-bold text-yellow-400">{{ auth()->user()->scans()->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-slate-800 rounded-lg p-4 border-l-4 border-green-500">
            <p class="text-gray-400 text-sm">Disetujui</p>
            <p class="text-2xl font-bold text-green-400">{{ auth()->user()->scans()->where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-slate-800 rounded-lg p-4 border-l-4 border-red-500">
            <p class="text-gray-400 text-sm">Ditolak</p>
            <p class="text-2xl font-bold text-red-400">{{ auth()->user()->scans()->where('status', 'rejected')->count() }}</p>
        </div>
    </div>

    <!-- Scan List -->
    <div class="bg-slate-800 rounded-xl overflow-hidden">
        @if($scans->count() > 0)
            <table class="w-full text-sm text-gray-300">
                <thead>
                    <tr class="bg-slate-900 border-b border-slate-700">
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Hasil AI</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Poin</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scans as $scan)
                        <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                            <td class="px-6 py-4 text-sm">
                                {{ $scan->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($scan->detected_items && count($scan->detected_items) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($scan->detected_items, 0, 2) as $item)
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-300 rounded text-xs">
                                                {{ $item['name'] ?? 'Item' }}
                                            </span>
                                        @endforeach
                                        @if(count($scan->detected_items) > 2)
                                            <span class="px-2 py-1 bg-gray-600/20 text-gray-300 rounded text-xs">
                                                +{{ count($scan->detected_items) - 2 }} lagi
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($scan->status === 'pending')
                                        bg-yellow-500/20 text-yellow-400
                                    @elseif($scan->status === 'approved')
                                        bg-green-500/20 text-green-400
                                    @else
                                        bg-red-500/20 text-red-400
                                    @endif">
                                    @if($scan->status === 'pending')
                                        ⏳ Pending
                                    @elseif($scan->status === 'approved')
                                        ✅ Approved
                                    @else
                                        ❌ Rejected
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($scan->poin_earned > 0)
                                    <span class="text-yellow-400 font-bold">+{{ $scan->poin_earned }}</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('user.scan.show', $scan) }}"
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded transition text-white text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-700">
                {{ $scans->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">Belum ada riwayat scan</p>
                <a href="{{ route('user.scan.create') }}" class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition inline-block">
                    Mulai Scan Sekarang
                </a>
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-blue-900/20 border border-blue-500 rounded-lg p-4">
        <p class="text-blue-300 text-sm">
            💡 <strong>Cara Kerja:</strong> Setelah kamu upload scan, admin akan mereview dalam waktu 1-24 jam. Jika disetujui, poin langsung masuk ke akun kamu!
        </p>
    </div>
</div>
@endsection
