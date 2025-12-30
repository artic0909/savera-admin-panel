<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageSetting;

class HomePageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'banner_1',
                'value' => 'assets/images/Banner-1.webp',
                'type' => 'image',
            ],
            [
                'key' => 'banner_2',
                'value' => 'assets/images/Banner-2.webp',
                'type' => 'image',
            ],
            [
                'key' => 'banner_3',
                'value' => 'assets/images/Bannerd-3.webp',
                'type' => 'image',
            ],
            [
                'key' => 'banner_4',
                'value' => 'assets/images/Banner-4.webp',
                'type' => 'image',
            ],
            [
                'key' => 'beginning_heading',
                'value' => 'For Every Beginning',
                'type' => 'text',
            ],
            [
                'key' => 'beginning_photos',
                'value' => json_encode([
                    'assets/images/s1.jpg',
                    'assets/images/s2.jpg',
                    'assets/images/s3.jpg',
                    'assets/images/s4.jpg',
                    'assets/images/s5.jpg',
                    'assets/images/s6.jpg',
                    'assets/images/s7.jpg',
                    'assets/images/s8.jpg',
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'moments_heading',
                'value' => 'For the moments that matter',
                'type' => 'text',
            ],
            [
                'key' => 'moments_videos',
                'value' => json_encode([
                    ['video' => 'assets/videos/website-1st-video.mp4', 'thumbnail' => 'assets/images/th-1.webp'],
                    ['video' => 'assets/videos/website-video-2nd.mp4', 'thumbnail' => 'assets/images/th-2.webp'],
                    ['video' => 'assets/videos/website-video-3rd.mp4', 'thumbnail' => 'assets/images/th-3.webp'],
                    ['video' => 'assets/videos/website-video-4th.mp4', 'thumbnail' => 'assets/images/th-4.webp'],
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'why_choose_heading',
                'value' => 'Why Choose Savera',
                'type' => 'text',
            ],
            [
                'key' => 'why_choose_photos',
                'value' => json_encode([
                    'assets/images/Banner-1.webp', // Placeholders if WhyChoose table is empty
                    'assets/images/Banner-2.webp',
                    'assets/images/Bannerd-3.webp',
                    'assets/images/Banner-4.webp',
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'why_choose_logo',
                'value' => 'assets/images/logo-icon.png',
                'type' => 'image',
            ],
            [
                'key' => 'store_front_image',
                'value' => 'assets/images/Priyanka-store-front-1.webp',
                'type' => 'image',
            ],
        ];

        foreach ($settings as $setting) {
            HomePageSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
