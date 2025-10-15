<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Celebrity;

class UpdateCelebritiesFieldsSeeder extends Seeder
{
    /**
     * Update existing celebrities with missing field data.
     */
    public function run(): void
    {
        $celebrities = Celebrity::all();
        
        foreach ($celebrities as $celebrity) {
            // Set default category if not set
            if (empty($celebrity->category)) {
                $category = $this->getCategoryFromProfession($celebrity->profession);
                $celebrity->category = $category;
            }
            
            // Set hourly_rate if not set but base_price exists
            if (empty($celebrity->hourly_rate) && !empty($celebrity->base_price)) {
                $celebrity->hourly_rate = $celebrity->base_price;
            }
            
            // Set a default hourly_rate if completely empty
            if (empty($celebrity->hourly_rate) && empty($celebrity->base_price)) {
                $celebrity->hourly_rate = 500; // Default rate
            }
            
            $celebrity->save();
        }
    }
    
    /**
     * Determine category based on profession
     */
    private function getCategoryFromProfession($profession)
    {
        $profession = strtolower($profession ?? '');
        
        if (str_contains($profession, 'actor') || str_contains($profession, 'actress')) {
            return 'Hollywood';
        } elseif (str_contains($profession, 'singer') || str_contains($profession, 'musician') || str_contains($profession, 'rapper')) {
            return 'Music';
        } elseif (str_contains($profession, 'athlete') || str_contains($profession, 'player') || str_contains($profession, 'sport')) {
            return 'Sports';
        } elseif (str_contains($profession, 'comedian') || str_contains($profession, 'comedy')) {
            return 'Comedy';
        } elseif (str_contains($profession, 'influencer') || str_contains($profession, 'youtuber') || str_contains($profession, 'tiktoker')) {
            return 'Social Media';
        } elseif (str_contains($profession, 'tv') || str_contains($profession, 'television') || str_contains($profession, 'host')) {
            return 'Television';
        } else {
            return 'Other';
        }
    }
}
