<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'target_type' => 'hopital_physique',
                'price' => 50000,
                'duration_unit' => 'month',
                'duration_value' => 1,
                'features' => ['Basic features', 'Up to 100 patients'],
                'is_active' => true,
            ],
            [
                'name' => 'Premium Plan',
                'target_type' => 'hopital_physique',
                'price' => 150000,
                'duration_unit' => 'month',
                'duration_value' => 1,
                'features' => ['Premium features', 'Unlimited patients', 'Advanced analytics'],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise Plan',
                'target_type' => 'clinique_privee',
                'price' => 500000,
                'duration_unit' => 'year',
                'duration_value' => 1,
                'features' => ['Enterprise features', 'Custom integrations', 'Dedicated support'],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('Subscription plans seeded successfully.');
    }
}
