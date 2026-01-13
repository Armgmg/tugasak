@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Setor Sampah</h2>

    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Jenis Sampah</label>
            <input type="text" name="jenis_sampah" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Berat (Kg)</label>
            <input type="number" name="berat" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Harga per Kg</label>
            <input type="number" name="harga" class="w-full border rounded p-2" required>
        </div>

        <button class="bg-teal-600 text-white px-4 py-2 rounded">
            Kirim Transaksi
        </button>
    </form>
</div>
@endsection
