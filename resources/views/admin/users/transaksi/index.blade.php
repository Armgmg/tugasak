@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Riwayat Transaksi</h1>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Jenis Sampah</th>
                <th class="px-4 py-3 text-left">Berat (Kg)</th>
                <th class="px-4 py-3 text-left">Harga</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $trx->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $trx->jenis_sampah }}</td>
                    <td class="px-4 py-3">{{ $trx->berat }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($trx->harga) }}</td>
                    <td class="px-4 py-3 font-bold text-teal-600">
                        Rp {{ number_format($trx->total) }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $trx->status === 'pending'
                                ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        Belum ada transaksi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
