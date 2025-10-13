<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Autograph Signing',
                'description' => 'Personal autograph signing sessions with your favorite celebrity',
                'base_price' => 150.00,
                'duration_minutes' => 30,
                'icon' => 'fas fa-signature',
                'sort_order' => 1,
            ],
            [
                'name' => 'Private Reservations',
                'description' => 'Exclusive private time with celebrities for intimate gatherings',
                'base_price' => 2500.00,
                'duration_minutes' => 120,
                'icon' => 'fas fa-lock',
                'sort_order' => 2,
            ],
            [
                'name' => 'Celebrity Meet and Greet',
                'description' => 'Personal meet and greet sessions with photo opportunities',
                'base_price' => 500.00,
                'duration_minutes' => 60,
                'icon' => 'fas fa-handshake',
                'sort_order' => 3,
            ],
            [
                'name' => 'Charity Foundation Events',
                'description' => 'Celebrity appearances at charity and foundation events',
                'base_price' => 5000.00,
                'duration_minutes' => 180,
                'icon' => 'fas fa-heart',
                'sort_order' => 4,
            ],
            [
                'name' => 'Product Endorsements',
                'description' => 'Celebrity endorsements for products and brands',
                'base_price' => 10000.00,
                'duration_minutes' => 240,
                'icon' => 'fas fa-star',
                'sort_order' => 5,
            ],
            [
                'name' => 'Nightclub Appearance',
                'description' => 'Celebrity appearances at nightclubs and entertainment venues',
                'base_price' => 7500.00,
                'duration_minutes' => 180,
                'icon' => 'fas fa-music',
                'sort_order' => 6,
            ],
            [
                'name' => 'Business Promotion & Adverts',
                'description' => 'Celebrity participation in business promotions and advertisements',
                'base_price' => 15000.00,
                'duration_minutes' => 480,
                'icon' => 'fas fa-bullhorn',
                'sort_order' => 7,
            ],
            [
                'name' => 'Tradeshow Appearance',
                'description' => 'Celebrity appearances at tradeshows and industry events',
                'base_price' => 3500.00,
                'duration_minutes' => 240,
                'icon' => 'fas fa-building',
                'sort_order' => 8,
            ],
            [
                'name' => 'Corporate Events',
                'description' => 'Celebrity hosting and appearances at corporate functions',
                'base_price' => 8000.00,
                'duration_minutes' => 300,
                'icon' => 'fas fa-briefcase',
                'sort_order' => 9,
            ],
        ];

        foreach ($services as $service) {
            ServiceType::create($service);
        }
    }
}