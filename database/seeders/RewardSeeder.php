<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Voucher Belanja Rp 50.000',
                'description' => 'Voucher belanja untuk membeli produk ramah lingkungan',
                'poin_required' => 100,
                'image' => 'voucher-50k.jpg',
                'status' => true,
            ],
            [
                'name' => 'Voucher Belanja Rp 100.000',
                'description' => 'Voucher belanja dengan nilai lebih besar',
                'poin_required' => 200,
                'image' => 'voucher-100k.jpg',
                'status' => true,
            ],
            [
                'name' => 'Tas Eco-Friendly',
                'description' => 'Tas belanja ramah lingkungan terbuat dari bahan daur ulang',
                'poin_required' => 150,
                'image' => 'tas-eco.jpg',
                'status' => true,
            ],
            [
                'name' => 'Botol Minum Stainless Steel',
                'description' => 'Botol minum tahan lama untuk mengurangi sampah plastik',
                'poin_required' => 120,
                'image' => 'botol-minum.jpg',
                'status' => true,
            ],
            [
                'name' => 'Diskon 20% Produk Organik',
                'description' => 'Diskon eksklusif untuk produk organik di marketplace kami',
                'poin_required' => 80,
                'image' => 'diskon-organik.jpg',
                'status' => true,
            ],
            [
                'name' => 'Paket Bibit Tanaman Gratis',
                'description' => 'Dapatkan paket bibit tanaman untuk ditanam di rumah',
                'poin_required' => 250,
                'image' => 'bibit-tanaman.jpg',
                'status' => true,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
