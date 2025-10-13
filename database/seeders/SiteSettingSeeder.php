<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::create([
            'site_name' => 'Celebrity Movie Agency',
            'site_email' => 'info@celebrityagency.com',
            'site_phone' => '+1-555-123-4567',
            'site_address' => '123 Hollywood Blvd, Los Angeles, CA 90028',
            'site_description' => 'Premier celebrity booking agency for all your entertainment needs. We connect you with the biggest stars for unforgettable experiences.',
            'about_content' => '<h3>About Celebrity Movie Agency</h3><p>With over 15 years of experience in the entertainment industry, Celebrity Movie Agency has established itself as the premier destination for celebrity bookings and entertainment services.</p><p>Our extensive network includes A-list actors, musicians, athletes, and influencers who are available for various events including meet & greets, corporate events, charity functions, and private appearances.</p><p>We pride ourselves on providing exceptional service and creating memorable experiences that exceed our clients\' expectations.</p>',
            'payment_enabled' => true,
            'payment_methods' => ['stripe', 'crypto', 'bank_transfer'],
            'theme_color' => 'dark-gold',
        ]);
    }
}
