<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Book Celebrity Experiences Like Never Before',
                'subtitle' => 'Connect with A-List Celebrities',
                'description' => 'From exclusive meet & greets to corporate events, create unforgettable memories with your favorite stars.',
                'image_path' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'cta_text' => 'Book Now',
                'cta_link' => '/book-celebrity',
                'order_position' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Exclusive Celebrity Events',
                'subtitle' => 'Premium Entertainment Solutions',
                'description' => 'Transform your events with star power. Professional celebrity bookings for corporate events, private parties, and special occasions.',
                'image_path' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'cta_text' => 'View Celebrities',
                'cta_link' => '/celebrities',
                'order_position' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Personalized Celebrity Experiences',
                'subtitle' => 'Tailored Just for You',
                'description' => 'Custom celebrity experiences designed around your vision. From intimate sessions to grand productions, we make it happen.',
                'image_path' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'cta_text' => 'Learn More',
                'cta_link' => '/services',
                'order_position' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
