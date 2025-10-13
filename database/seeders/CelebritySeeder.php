<?php

namespace Database\Seeders;

use App\Models\Celebrity;
use Illuminate\Database\Seeder;

class CelebritySeeder extends Seeder
{
    public function run(): void
    {
        $celebrities = [
            [
                'name' => 'Alexandra Stone',
                'bio' => 'Award-winning actress known for her roles in blockbuster action films and romantic comedies. With over 20 years in the industry, Alexandra has captivated audiences worldwide.',
                'profession' => 'Actress',
                'base_price' => 5000.00,
                'rating' => 5,
                'achievements' => 'Academy Award Winner, Golden Globe Winner, BAFTA Award Winner',
                'social_media' => [
                    'instagram' => '@alexandrastone',
                    'twitter' => '@alexstone',
                    'facebook' => 'AlexandraStoneOfficial'
                ],
            ],
            [
                'name' => 'Marcus Williams',
                'bio' => 'International music sensation and multi-platinum recording artist. Marcus has topped charts in over 50 countries and sold more than 100 million records worldwide.',
                'profession' => 'Musician',
                'base_price' => 7500.00,
                'rating' => 5,
                'achievements' => 'Grammy Award Winner, Billboard Artist of the Year, World Music Award Winner',
                'social_media' => [
                    'instagram' => '@marcuswilliamsmusic',
                    'twitter' => '@marcuswilliams',
                    'spotify' => 'Marcus Williams'
                ],
            ],
            [
                'name' => 'Diana Rodriguez',
                'bio' => 'Acclaimed television and film actress, known for her versatile performances and humanitarian work. Diana is also a UN Goodwill Ambassador.',
                'profession' => 'Actress',
                'base_price' => 4500.00,
                'rating' => 5,
                'achievements' => 'Emmy Award Winner, SAG Award Winner, Humanitarian Award Recipient',
                'social_media' => [
                    'instagram' => '@dianarodriguez',
                    'twitter' => '@diana_rodriguez'
                ],
            ],
            [
                'name' => 'James Parker',
                'bio' => 'Professional athlete turned actor and entrepreneur. Former Olympic champion and current business mogul with ventures in sports and entertainment.',
                'profession' => 'Actor/Athlete',
                'base_price' => 6000.00,
                'rating' => 4,
                'achievements' => 'Olympic Gold Medalist, Sports Hall of Fame Inductee, Entrepreneur of the Year',
                'social_media' => [
                    'instagram' => '@jamesparkerofficial',
                    'twitter' => '@jamesparker',
                    'linkedin' => 'James Parker'
                ],
            ],
            [
                'name' => 'Sophia Chen',
                'bio' => 'Rising star in Hollywood with breakout performances in critically acclaimed independent films. Known for her method acting and dedication to her craft.',
                'profession' => 'Actress',
                'base_price' => 3000.00,
                'rating' => 4,
                'achievements' => 'Independent Spirit Award Winner, Critics Choice Award Nominee, Rising Star Award',
                'social_media' => [
                    'instagram' => '@sophiachen',
                    'twitter' => '@sophia_chen'
                ],
            ],
            [
                'name' => 'Michael Thompson',
                'bio' => 'Veteran comedian and television host with over 30 years of experience entertaining audiences. Known for his wit and engaging personality.',
                'profession' => 'Comedian/TV Host',
                'base_price' => 4000.00,
                'rating' => 5,
                'achievements' => 'Comedy Central Hall of Fame, Television Host of the Year, Lifetime Achievement Award',
                'social_media' => [
                    'instagram' => '@michaelthompsoncomedy',
                    'twitter' => '@mikethompson',
                    'youtube' => 'Michael Thompson Comedy'
                ],
            ],
        ];

        foreach ($celebrities as $celebrity) {
            Celebrity::create($celebrity);
        }
    }
}
