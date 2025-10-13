<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Johnson',
                'position' => 'Event Coordinator, Microsoft',
                'message' => 'Celebrity Movie Agency exceeded our expectations! The meet and greet with Alexandra Stone was perfectly organized, and our employees were thrilled. Professional service from start to finish.',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'David Martinez',
                'position' => 'Charity Foundation Director',
                'message' => 'Working with this agency for our charity event was amazing. Marcus Williams\' appearance helped us raise 300% more than our target. Highly recommended!',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Emily Chen',
                'position' => 'Marketing Director, TechCorp',
                'message' => 'The product endorsement campaign with Diana Rodriguez was a huge success. Sales increased by 150% in the first quarter. Professional and reliable service.',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Robert Wilson',
                'position' => 'Private Client',
                'message' => 'Booked a private meet and greet for my daughter\'s birthday. James Parker was incredible - so down to earth and made her day unforgettable. Worth every penny!',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Lisa Thompson',
                'position' => 'Corporate Event Planner',
                'message' => 'Michael Thompson hosted our annual company dinner and had everyone in stitches. The booking process was smooth and the team was very professional.',
                'rating' => 4,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Andrew Davis',
                'position' => 'Nightclub Owner',
                'message' => 'Sophia Chen\'s appearance at our grand opening was the talk of the town. The agency handled all the details perfectly. Will definitely book again!',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
