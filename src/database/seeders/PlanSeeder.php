<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'max_users' => 100,
                'price' => 0,
                'max_requests_per_day' => 1000,
                'is_active' => true,
                'description' => 'Pour les petits partenaires',
            ],
            [
                'name' => 'Basic',
                'max_users' => 500,
                'price' => 29,
                'max_requests_per_day' => 5000,
                'is_active' => true,
                'description' => 'Pour les partenaires moyens',
            ],
            [
                'name' => 'Pro',
                'max_users' => 2000,
                'price' => 99,
                'max_requests_per_day' => 20000,
                'is_active' => true,
                'description' => 'Pour les grands partenaires',
            ],
            [
                'name' => 'Enterprise',
                'max_users' => null,
                'price' => null,
                'max_requests_per_day' => null,
                'is_active' => true,
                'description' => 'Pour les partenaires premium',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
