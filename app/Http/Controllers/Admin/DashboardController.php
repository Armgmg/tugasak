<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalMember = User::where('role', 'user')->count();

        // User terbaru (LIMIT 5)
        $latestUsers = User::latest()->take(5)->get();

        // Grafik user per bulan (tahun ini)
        $usersPerMonth = User::selectRaw("DATE_FORMAT(created_at, '%m') as month, COUNT(*) as total")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 1));
            $monthStr = str_pad($i, 2, '0', STR_PAD_LEFT);
            $data[] = $usersPerMonth->firstWhere('month', $monthStr)->total ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmin',
            'totalMember',
            'latestUsers',
            'labels',
            'data'
        ));
    }
}
