<?php

namespace Database\Seeders;
use App\Models\BloodBank;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloodBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BloodBank::updateOrCreate(
            ['code' => 'BB-001'],
            [
                'name' => 'Central Blood Bank',
                'address' => 'Main Hospital Road',
                'contact_number' => '9876543210',
                'is_active' => true,
            ]
        );

        BloodBank::updateOrCreate(
            ['code' => 'BB-002'],
            [
                'name' => 'City Emergency Blood Bank',
                'address' => 'City Medical Center',
                'contact_number' => '9876500000',
                'is_active' => true,
            ]
        );


    }
}
