<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 5; $i++) {
            $companyName = $faker->company;
            Partner::create([
                'name' => $companyName,
                'logo_url' => 'https://placehold.co/200x200?text=' . urlencode($companyName),
            ]);
        }
    }
}
