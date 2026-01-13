@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-white">Konfirmasi Scan AI 📸</h1>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-800 rounded-lg p-4 border-l-4 border-yellow-500">
                <p class="text-gray-400 text-sm">Menunggu Konfirmasi</p>
                <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-slate-800 rounded-lg p-4 border-l-4 border-green-500">
                <p class="text-gray-400 text-sm">Disetujui</p>
                <p class="text-2xl font-bold text-green-400">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-slate-800 rounded-lg p-4 border-l-4 border-red-500">
                <p class="text-gray-400 text-sm">Ditolak</p>
                <p class="text-2xl font-bold text-red-400">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                {{ $message }}
            </div>
        @endif

        <!-- Scans Table -->
        <div class="bg-slate-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm text-gray-300">
                <thead>
                    <tr class="bg-slate-900 border-b border-slate-700">
                        <th class="px-6 py-4 text-left">User</th>
                        <th class="px-6 py-4 text-left">Hasil AI</th>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scans as $scan)
                        <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-white">{{ $scan->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $scan->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($scan->detected_items)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($scan->detected_items as $item)
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-300 rounded text-xs">
                                                {{ $item['name'] ?? 'Item' }} ({{ round($item['confidence'] ?? 0, 1) }}%)
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Tidak ada data</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $scan->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
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
                                        Auto Verified
                                    @else
                                        {{ ucfirst($scan->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.scans.show', $scan) }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg transition inline-block">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada scan yang menunggu konfirmasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $scans->links() }}
        </div>
    </div>
@endsection