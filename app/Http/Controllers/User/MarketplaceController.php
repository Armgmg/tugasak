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
        // Kriteria:
        // C1: Harga Poin (Cost) - Bobot 0.5
        // C2: Popularitas/Jumlah Tukar (Benefit) - Bobot 0.5

        $weights = [
            'C1' => 0.5,
            'C2' => 0.5
        ];

        // 1. Persiapkan Data & Hitung Popularitas
        $data = [];
        foreach ($rewards as $reward) {
            // Hitung popularitas berdasarkan jumlah transaksi dengan nama reward ini
            // Asumsi: kolom 'reward' di tabel transactions menyimpan nama reward
            $popularity = \App\Models\Transaction::where('reward', $reward->name)
                ->where('tipe_transaksi', 'tukar')
                ->count();

            $data[] = [
                'id' => $reward->id,
                'reward_obj' => $reward, // Simpan objek asli
                'C1' => $reward->poin_required, // Cost
                'C2' => $popularity, // Benefit
            ];
        }

        // 2. Cari Nilai Min/Max untuk Normalisasi
        $c1_values = array_column($data, 'C1');
        $c2_values = array_column($data, 'C2');

        $min_c1 = !empty($c1_values) ? min($c1_values) : 0;
        $max_c2 = !empty($c2_values) ? max($c2_values) : 0;

        // 3. Normalisasi & Hitung Nilai Preferensi (V)
        foreach ($data as &$row) {
            // Normalisasi C1 (Cost): Min / Nilai
            $r1 = ($row['C1'] > 0) ? ($min_c1 / $row['C1']) : 0;

            // Normalisasi C2 (Benefit): Nilai / Max
            // Jika max_c2 0 (belum ada transaksi), maka nilai 0
            $r2 = ($max_c2 > 0) ? ($row['C2'] / $max_c2) : 0;

            // Hitung skor V
            $v = ($weights['C1'] * $r1) + ($weights['C2'] * $r2);

            $row['score'] = $v;
        }
        unset($row); // break reference

        // 4. Perankingan (Sort Descending by Score)
        usort($data, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 5. Kembalikan array reward yang sudah terurut
        // Kita ambil kembali objek reward dari array data
        $sortedRewards = collect(array_column($data, 'reward_obj'));

        // Kirim $rewards (sorted) ke view
        return view('marketplace', ['rewards' => $sortedRewards]);
    }
}
