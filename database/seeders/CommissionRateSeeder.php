<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionRate;
use App\Models\CommissionBracket;

class CommissionRateSeeder extends Seeder
{
    public function run(): void
    {
        $rate = CommissionRate::create([
            'service_type' => 'price_based_commissions',
            'activation_fee' => 4000,
            'commission_percentage' => 0,
            'is_active' => true,
        ]);

        $brackets = [
            [
                'min_price' => 0,
                'max_price' => 15000,
                'percentage' => 15,
                'order' => 1,
            ],
            [
                'min_price' => 15001,
                'max_price' => 30000,
                'percentage' => 20,
                'order' => 2,
            ],
            [
                'min_price' => 30001,
                'max_price' => null,
                'percentage' => 25,
                'order' => 3,
            ],
        ];

        foreach ($brackets as $bracket) {
            $rate->brackets()->create($bracket);
        }

        $this->command->info('Commission rates seeded successfully.');
    }
}
