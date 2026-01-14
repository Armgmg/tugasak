<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $transactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => 'transaction',
                    'tipe_transaksi' => $transaction->tipe_transaksi,
                    'jenis_sampah' => $transaction->jenis_sampah,
                    'berat' => $transaction->berat,
                    'poin' => $transaction->poin,
                    'status' => $transaction->status ?? 'completed',
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ];
            });

        // Get scans
        $scans = $user->scans()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($scan) {
                return [
                    'id' => $scan->id,
                    'type' => 'scan',
                    'image_path' => $scan->image_path ? asset('storage/' . $scan->image_path) : null,
                    'detected_items' => $scan->detected_items,
                    'status' => $scan->status, // pending, approved, rejected
                    'total_weight' => $scan->total_weight,
                    'poin_earned' => $scan->poin_earned,
                    'admin_notes' => $scan->admin_notes,
                    'created_at' => $scan->created_at,
                    'updated_at' => $scan->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $transactions,
                'scans' => $scans,
            ]
        ]);
    }
}
