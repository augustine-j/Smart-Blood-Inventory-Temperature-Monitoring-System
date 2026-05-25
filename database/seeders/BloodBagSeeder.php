<?php

namespace Database\Seeders;
use App\Models\BloodBag;
use App\Models\Refrigerator;
use Faker\Core\Blood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloodBagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $refrigerator  = Refrigerator::where('code', 'RF-001')->first();

        $bags = [
            [
                'bag_number' => 'BAG-001',
                'blood_group' => 'A+',
                'donor_name' => 'Rahul',
                'collection_date' => now()->subDays(20)->toDateString(),
                'expiry_date' => now()->addDays(15)->toDateString(),
                'quantity_ml' => 450,
                'status' => 'available',
            ],
            [
                'bag_number' => 'BAG-002',
                'blood_group' => 'B+',
                'donor_name' => 'Akhil',
                'collection_date' => now()->subDays(34)->toDateString(),
                'expiry_date' => now()->addHours(20)->toDateString(),
                'quantity_ml' => 450,
                'status' => 'available',
            ],
            [
                'bag_number' => 'BAG-003',
                'blood_group' => 'O-',
                'donor_name' => 'Kevin',
                'collection_date' => now()->subDays(40)->toDateString(),
                'expiry_date' => now()->subDay()->toDateString(),
                'quantity_ml' => 350,
                'status' => 'expired',
            ],
            [
                'bag_number' => 'BAG-004',
                'blood_group' => 'AB+',
                'donor_name' => 'John',
                'collection_date' => now()->subDays(10)->toDateString(),
                'expiry_date' => now()->addDays(25)->toDateString(),
                'quantity_ml' => 450,
                'status' => 'reserved',
            ],
        ];

        foreach($bags as $bag){
            BloodBag::updateOrCreate(
                ['bag_number' => $bag['bag_number']],
                array_merge($bag,[
                    'refrigerator_id' => $refrigerator->id,
                ])
            );
        }
    }
}
