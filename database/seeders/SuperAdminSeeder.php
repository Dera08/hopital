<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::updateOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('supeAD'),
                'access_code' => 'ADMIN202',
                'wallet_balance' => 0,
            ]
        );

        $this->command->info('Super Admin créé avec succès.');
    }
}
