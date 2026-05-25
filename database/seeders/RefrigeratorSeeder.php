<?php

namespace Database\Seeders;
use App\Models\BloodBank;
use App\Models\Refrigerator;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefrigeratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $central = BloodBank::where('code','BB-001')->first();
        $city = BloodBank::where('code', 'BB-002')->first();

        Refrigerator::updateOrCreate(
            ['code' => 'RF-001'],
            [
                'blood_bank_id' => $central->id,
                'name' => 'Refrigerator A',
                'location' => 'Ground Floor Storage',
                'status' => 'active',
            ]
        );


         Refrigerator::updateOrCreate(
            ['code' => 'RF-002'],
            [
                'blood_bank_id' => $central->id,
                'name' => 'Refrigerator B',
                'location' => 'Emergency Wing',
                'status' => 'active',
            ]
        );


        Refrigerator::updateOrCreate(
            ['code' => 'RF-003'],
            [
                'blood_bank_id' => $city->id,
                'name' => 'Refrigerator C',
                'location' => 'Main Storage',
                'status' => 'maintenance',
            ]
        );
    }
}
