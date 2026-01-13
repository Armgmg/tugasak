<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        $totalWeight = Transaction::where('user_id', $user->id)
            ->where('tipe_transaksi', 'setor')
            ->where('status', 'approved')
            ->sum('berat');

        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();


        $chartLabels = [];
        $chartData = [];
        $days = 7;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->isoFormat('dddd'); // Day name


            $dailyPoints = Transaction::where('user_id', $user->id)
                ->whereDate('created_at', $date->toDateString())
                ->where('status', 'approved')
                ->where('poin', '>', 0) // Only count gains
                ->sum('poin');

            $chartData[] = $dailyPoints;
        }

        return view('dashboard', compact(
            'totalWeight',
            'recentTransactions',
            'chartLabels',
            'chartData'
        ));
    }
}
