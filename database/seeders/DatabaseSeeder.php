<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $staff = User::updateOrCreate(
            ['email' => 'staff@example.com'],
            ['name' => 'Blood Bank Staff',
                'password' =>Hash::make('password'),
                'role' => 'staff'
            ]
        );

        $monitor = User::updateOrCreate(
            ['email' => 'monitor@example.com'],
            ['name' => 'Monitoring User',
                'password' => Hash::make('password'),
                'role' => 'monitoring',
            ]
        );

        $this->call([
            BloodBankSeeder::class,
            RefrigeratorSeeder::class,
            BloodBagSeeder::class,
        ]);

        $bloodBankIds = \App\Models\BloodBank::pluck('id');

        $admin->bloodBanks()->sync($bloodBankIds);
        $staff->bloodBanks()->sync($bloodBankIds);
        $monitor->bloodBanks()->sync($bloodBankIds);


    }
}
