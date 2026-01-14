<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SawController extends Controller
{
    public function index(Request $request)
    {
        // 1. Definisi Alternatif & Kriteria (Default)
        $alternatives = [
            'A1' => 'Pulsa / Paket Data',
            'A2' => 'Produk Ramah Lingkungan',
            'A3' => 'Token Listrik / E-Wallet',
        ];

        // Default Criteria
        $criteria = [
            'C1' => ['name' => 'Berat (kg)', 'type' => 'benefit', 'weight' => 3],
            'C2' => ['name' => 'Nilai Poin', 'type' => 'benefit', 'weight' => 4],
            'C3' => ['name' => 'Harga Poin', 'type' => 'cost', 'weight' => 5],
        ];

        // Default Matrix Data
        $data = [
            'A1' => ['C1' => 4, 'C2' => 2, 'C3' => 1],
            'A2' => ['C1' => 3, 'C2' => 3, 'C3' => 2],
            'A3' => ['C1' => 5, 'C2' => 4, 'C3' => 3],
        ];

        // Override with user input if method is POST and data exists
        if ($request->isMethod('post')) {
            if ($request->has('weights')) {
                foreach ($request->input('weights') as $key => $weight) {
                    if (isset($criteria[$key])) {
                        $criteria[$key]['weight'] = (float) $weight;
                    }
                }
            }

            if ($request->has('matrix')) {
                foreach ($request->input('matrix') as $altKey => $scores) {
                    foreach ($scores as $critKey => $score) {
                        if (isset($data[$altKey][$critKey])) {
                            $data[$altKey][$critKey] = (float) $score;
                        }
                    }
                }
            }
        }

        // 3. Normalisasi Matriks (R)
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
                    $normalized[$altCode][$critCode] = $minMax[$critCode] != 0
                        ? $scores[$critCode] / $minMax[$critCode]
                        : 0;
                } else {
                    $normalized[$altCode][$critCode] = $scores[$critCode] != 0
                        ? $minMax[$critCode] / $scores[$critCode]
                        : 0;
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
