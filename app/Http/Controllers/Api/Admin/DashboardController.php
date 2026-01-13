<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Scan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_points_distributed' => User::sum('poin'),
            'total_waste_collected' => Transaction::where('tipe_transaksi', 'setor')
                ->where('status', 'approved')
                ->sum('berat'),
            'pending_scans' => Scan::where('status', 'pending')->count(),
        ];

        // Recent Scans
        $recentScans = Scan::with('user')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'recent_scans' => $recentScans
            ]
        ]);
    }
}
