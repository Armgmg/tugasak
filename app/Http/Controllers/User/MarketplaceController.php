<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index()
    {
        // Ambil semua reward aktif
        $rewards = Reward::where('status', true)->get();

        // Jika tidak ada reward, langsung kembalikan view
        if ($rewards->isEmpty()) {
            return view('marketplace', compact('rewards'));
        }

        // ==========================================
        // IMPLEMENTASI METODE SAW (Simple Additive Weighting)
        // ==========================================
        // Referensi Sesuai Perhitungan User:
        // C1: Berat (kg) - Benefit - Bobot 3
        // C2: Nilai Poin - Benefit - Bobot 4
        // C3: Harga Poin - Cost    - Bobot 5

        $weights = [
            'C1' => 3,
            'C2' => 4,
            'C3' => 5
        ];

        // 1. Persiapkan Data
        // Menggunakan data dari database (field: berat, nilai_poin, poin_required)
        $data = [];
        foreach ($rewards as $reward) {
            $data[] = [
                'id' => $reward->id,
                'reward_obj' => $reward,
                'C1' => (float) $reward->berat,       // Berat (Benefit)
                'C2' => (float) $reward->nilai_poin,  // Nilai Poin (Benefit)
                'C3' => (float) $reward->poin_required // Harga Poin (Cost)
            ];
        }

        // 2. Cari Nilai Min/Max untuk Normalisasi
        $c1_values = array_column($data, 'C1');
        $c2_values = array_column($data, 'C2');
        $c3_values = array_column($data, 'C3');

        $max_c1 = !empty($c1_values) ? max($c1_values) : 0;
        $max_c2 = !empty($c2_values) ? max($c2_values) : 0;
        $min_c3 = !empty($c3_values) ? min($c3_values) : 0;

        // 3. Normalisasi & Hitung Nilai Preferensi (V)
        foreach ($data as &$row) {
            // Normalisasi C1 (Benefit): Nilai / Max
            $r1 = ($max_c1 > 0) ? ($row['C1'] / $max_c1) : 0;

            // Normalisasi C2 (Benefit): Nilai / Max
            $r2 = ($max_c2 > 0) ? ($row['C2'] / $max_c2) : 0;

            // Normalisasi C3 (Cost): Min / Nilai
            $r3 = ($row['C3'] > 0) ? ($min_c3 / $row['C3']) : 0;

            // Hitung skor V (Jumlah Perkalian Bobot * Rating)
            // Rumus: V = (W1 * R1) + (W2 * R2) + (W3 * R3)
            $v = ($weights['C1'] * $r1) +
                ($weights['C2'] * $r2) +
                ($weights['C3'] * $r3);

            $row['score'] = $v;
        }
        unset($row); // break reference

        // 4. Perankingan (Sort Descending by Score)
        usort($data, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 5. Kembalikan array reward yang sudah terurut
        $sortedRewards = collect(array_column($data, 'reward_obj'));

        return view('marketplace', ['rewards' => $sortedRewards]);
    }
}
