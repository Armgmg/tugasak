<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $totalPoin = $user->poin ?? 0;

        // Statistik poin
        $statistics = [
            'total_poin' => $totalPoin,
            'total_scan' => $user->transactions()
                ->where('tipe_transaksi', 'setor')
                ->count(),
            'total_berat' => $user->transactions()
                ->where('tipe_transaksi', 'setor')
                ->sum('berat'),
            'total_redeem' => $user->transactions()
                ->where('tipe_transaksi', 'tukar')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }
}
