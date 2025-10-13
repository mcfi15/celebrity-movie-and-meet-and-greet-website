<?php

namespace Database\Seeders;

use App\Models\Celebrity;
use App\Models\ServiceType;
use App\Models\CelebrityService;
use Illuminate\Database\Seeder;

class CelebrityServiceSeeder extends Seeder
{
    public function run(): void
    {
        $celebrities = Celebrity::all();
        $serviceTypes = ServiceType::all();

        foreach ($celebrities as $celebrity) {
            foreach ($serviceTypes as $serviceType) {
                // Calculate price based on celebrity base price and service base price
                $price = $celebrity->base_price + $serviceType->base_price;
                
                // Add some variation based on celebrity and service type
                if ($serviceType->name === 'Product Endorsements' || $serviceType->name === 'Business Promotion & Adverts') {
                    $price *= 1.5; // Premium for endorsements
                }
                
                if ($celebrity->rating === 5) {
                    $price *= 1.2; // Premium for top-rated celebrities
                }

                CelebrityService::create([
                    'celebrity_id' => $celebrity->id,
                    'service_type_id' => $serviceType->id,
                    'price' => round($price, 2),
                    'description' => "Exclusive {$serviceType->name} service with {$celebrity->name}",
                    'is_available' => true,
                ]);
            }
        }
    }
}
