<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CryptoPrice;

class CryptoPricesSeeder extends Seeder
{
    public function run(): void
    {
        $cryptoPrices = [
            [
                'cryptocurrency' => 'bitcoin',
                'price' => 102770.50,
                'change_24h' => 0.38,
                'created_at' => now()
            ],
            [
                'cryptocurrency' => 'ethereum',
                'price' => 3376.17,
                'change_24h' => 0.84,
                'created_at' => now()
            ],
            [
                'cryptocurrency' => 'solana',
                'price' => 158.01,
                'change_24h' => 0.01,
                'created_at' => now()
            ],
            [
                'cryptocurrency' => 'cardano',
                'price' => 0.5323,
                'change_24h' => -1.00,
                'created_at' => now()
            ],
            [
                'cryptocurrency' => 'dogecoin',
                'price' => 0.1620,
                'change_24h' => -1.33,
                'created_at' => now()
            ]
        ];

        foreach ($cryptoPrices as $price) {
            CryptoPrice::create($price);
        }

        $this->command->info('✅ Sample crypto prices seeded successfully!');
    }
}