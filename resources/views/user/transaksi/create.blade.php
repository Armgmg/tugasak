@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Tambah Transaksi</h1>

    <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Nama Transaksi</label>
            <input type="text" name="nama"
                   class="w-full p-2 rounded text-black">
        </div>

        <button class="bg-green-600 px-4 py-2 rounded">
            Simpan Transaksi
        </button>
    </form>
@endsection
