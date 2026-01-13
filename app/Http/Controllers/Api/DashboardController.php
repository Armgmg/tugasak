<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. User Stats
        $totalPoin = $user->poin ?? 0;
        $totalWeight = \App\Models\Transaction::where('user_id', $user->id)
            ->where('tipe_transaksi', 'setor')
            ->where('status', 'approved')
            ->sum('berat');

        $totalScans = \App\Models\Scan::where('user_id', $user->id)->count();
        $approvedScans = \App\Models\Scan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        $recentTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => 'transaction',
                    'description' => $transaction->tipe_transaksi == 'setor' ? 'Setor Sampah' : 'Tukar Reward',
                    'poin' => $transaction->poin,
                    'created_at' => $transaction->created_at,
                ];
            });

        $recentScans = \App\Models\Scan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($scan) {
                return [
                    'id' => $scan->id,
                    'type' => 'scan',
                    'description' => 'Scan Sampah',
                    'status' => $scan->status,
                    'poin' => $scan->poin_earned ?? 0,
                    'created_at' => $scan->created_at,
                ];
            });

        $recentActivity = $recentTransactions->merge($recentScans)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();


        $chartLabels = [];
        $chartData = [];
        $days = 7;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = \Illuminate\Support\Carbon::now()->subDays($i);
            $chartLabels[] = $date->isoFormat('ddd'); 

  
            $dailyPoints = \App\Models\Transaction::where('user_id', $user->id)
                ->whereDate('created_at', $date->toDateString())
                ->where('status', 'approved')
                ->where('poin', '>', 0) // Only count gains
                ->sum('poin');

            $chartData[] = $dailyPoints;
        }

        // 4. Scan Statistics
        $scanStats = [
            'total' => $totalScans,
            'pending' => \App\Models\Scan::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => $approvedScans,
            'rejected' => \App\Models\Scan::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        $dashboardData = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'joined_date' => $user->created_at->format('d M Y'),
            ],
            'stats' => [
                'total_poin' => $totalPoin,
                'total_weight' => $totalWeight,
                'total_scans' => $totalScans,
                'approved_scans' => $approvedScans,
            ],
            'scan_stats' => $scanStats,
            'recent_activity' => $recentActivity,
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ],
            'greeting' => $this->getGreeting(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => $dashboardData
        ]);
    }

    private function getGreeting()
    {
        $hour = now()->format('H');

        if ($hour >= 5 && $hour < 12) {
            return 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
}