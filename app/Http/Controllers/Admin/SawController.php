<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SawController extends Controller
{
    public function index()
    {
        // 1. Definisi Alternatif & Kriteria
        // Sesuai Excel user
        $alternatives = [
            'A1' => 'Pulsa / Paket Data',
            'A2' => 'Produk Ramah Lingkungan',
            'A3' => 'Token Listrik / E-Wallet',
        ];

        // C1: Berat (kg), C2: Nilai Poin, C3: Harga Poin
        $criteria = [
            'C1' => ['name' => 'Berat (kg)', 'type' => 'benefit', 'weight' => 3],
            'C2' => ['name' => 'Nilai Poin', 'type' => 'benefit', 'weight' => 4],
            'C3' => ['name' => 'Harga Poin', 'type' => 'cost', 'weight' => 5],
        ];

        // 2. Matriks Awal (X)
        // Cek apakah ada input simulasi dari user?
        $request = request();
        if ($request->has('values')) {
            // Gunakan data simuasi
            $data = $request->input('values');
            // Pastikan format angka (casting)
            foreach ($data as $k => $v) {
                foreach ($v as $c => $val) {
                    $data[$k][$c] = floatval($val);
                }
            }
        } else {
            // Default: Data dari Excel
            $data = [
                'A1' => ['C1' => 4, 'C2' => 2, 'C3' => 1],
                'A2' => ['C1' => 3, 'C2' => 3, 'C3' => 2],
                'A3' => ['C1' => 5, 'C2' => 4, 'C3' => 3],
            ];
        }

        // 3. Normalisasi Matriks (R)
        // Cari Min/Max tiap kolom dulu
        $minMax = [];
        foreach ($criteria as $code => $info) {
            $columnData = array_column($data, $code);
            if ($info['type'] == 'benefit') {
                $minMax[$code] = max($columnData);
            } else {
                $minMax[$code] = min($columnData);
            }
        }

        $normalized = [];
        foreach ($data as $altCode => $scores) {
            foreach ($criteria as $critCode => $info) {
                if ($info['type'] == 'benefit') {
                    // Rumus Benefit: Nilai / Max
                    $normalized[$altCode][$critCode] = $scores[$critCode] / $minMax[$critCode];
                } else {
                    // Rumus Cost: Min / Nilai
                    $normalized[$altCode][$critCode] = $minMax[$critCode] / $scores[$critCode];
                }
            }
        }

        // 4. Perankingan (V)
        $ranks = [];
        foreach ($normalized as $altCode => $scores) {
            $totalScore = 0;
            foreach ($criteria as $critCode => $info) {
                $totalScore += $scores[$critCode] * $info['weight'];
            }
            $ranks[] = [
                'code' => $altCode,
                'name' => $alternatives[$altCode],
                'score' => $totalScore
            ];
        }

        // Sort dari terbesar ke terkecil
        usort($ranks, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return view('admin.rewards.saw', compact('alternatives', 'criteria', 'data', 'normalized', 'ranks'));
    }
}
