<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Transaction::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function approve($id)
    {
        $transaction = Transaction::with('user')->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($transaction->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah disetujui sebelumnya'
            ], 400);
        }

        // Tambahkan poin ke user
        $transaction->user->increment('poin', $transaction->poin);

        // Update status transaksi
        $transaction->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disetujui',
            'data' => $transaction
        ]);
    }

    public function reject($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya transaksi pending yang bisa ditolak'
            ], 400);
        }

        $transaction->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditolak',
            'data' => $transaction
        ]);
    }
}
