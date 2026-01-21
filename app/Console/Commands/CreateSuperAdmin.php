<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $superAdmin = \App\Models\SuperAdmin::updateOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('supeAD'),
                'access_code' => 'ADMIN2026',
            ]
        );

        $this->info('Super Admin created successfully.');
        $this->info('Email: admin@system.com');
        $this->info('Password: supeAD');
        $this->info('Access Code: ADMIN2026');
    }
}
