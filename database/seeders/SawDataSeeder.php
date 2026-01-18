<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;

class SawDataSeeder extends Seeder
{
    public function run()
    {
        // Data from User's Excel
        // A1: Pulsa / Paket Data -> Berat 4, Nilai 2
        Reward::where('name', 'Pulsa / Paket Data')->update(['berat' => 4, 'nilai_poin' => 2]);

        // A2: Produk Ramah Lingkungan -> Berat 3, Nilai 3
        Reward::where('name', 'Produk Ramah Lingkungan')->update(['berat' => 3, 'nilai_poin' => 3]);

        // A3: Token Listrik / E-Wallet -> Berat 5, Nilai 4
        Reward::where('name', 'Token Listrik atau E-Wallet')->update(['berat' => 5, 'nilai_poin' => 4]);

        echo "SAW Data Updated in Database.\n";
    }
}
