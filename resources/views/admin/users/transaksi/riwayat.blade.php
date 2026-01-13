@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Riwayat Transaksi</h3>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Sampah</th>
                <th>Berat</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->jenis_sampah }}</td>
                <td>{{ $t->berat }} Kg</td>
                <td>Rp {{ number_format($t->total) }}</td>
                <td>
                    @if($t->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($t->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>{{ $t->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
