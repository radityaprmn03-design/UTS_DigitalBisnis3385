<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds with prestigious tech & campus partners.
     */
    public function run(): void
    {
        Partner::truncate();

        $partners = [
            [
                'name' => 'Universitas AMIKOM',
                'logo_url' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=300&auto=format&fit=crop',
            ],
            [
                'name' => 'Midtrans Payment',
                'logo_url' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=300&auto=format&fit=crop',
            ],
            [
                'name' => 'Google Cloud',
                'logo_url' => 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?w=300&auto=format&fit=crop',
            ],
            [
                'name' => 'Vercel Serverless',
                'logo_url' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=300&auto=format&fit=crop',
            ],
            [
                'name' => 'GitHub Education',
                'logo_url' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=300&auto=format&fit=crop',
            ],
            [
                'name' => 'Telkom Indonesia',
                'logo_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=300&auto=format&fit=crop',
            ],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}
