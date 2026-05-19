<?php

namespace Database\Seeders;

use App\Models\PresentationMonth;
use App\Models\Slide;
use App\Models\SlideStatus;
use App\Models\SlideValue;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@pmt.com']);
        $admin->forceFill([
            'name' => 'Admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ])->save();

        $slides = [
            1 => 'Slide 1',
            2 => 'Slide 2',
            3 => 'Slide 3',
            4 => 'Slide 4',
            5 => 'Slide 5',
            6 => 'Slide 6',
            7 => 'Slide 7',
            8 => 'Slide 8',
            9 => 'Əsas fəaliyyət göstəriciləri',
            10 => 'Əsas fəaliyyət göstəriciləri: dinamika, tendensiya və müqaisələr',
        ];

        foreach ($slides as $number => $title) {
            Slide::updateOrCreate(
                ['slide_number' => $number],
                ['title' => $title, 'is_active' => true]
            );
        }

        PresentationMonth::query()->update(['is_active' => false]);

        $month = PresentationMonth::updateOrCreate(
            ['year' => 2026, 'month' => 5],
            ['name' => 'May 2026', 'is_active' => true]
        );

        foreach (Slide::all() as $slide) {
            SlideStatus::updateOrCreate(
                [
                    'presentation_month_id' => $month->id,
                    'slide_id' => $slide->id,
                ],
                [
                    'status' => 'in_progress',
                    'completed_by' => null,
                    'completed_at' => null,
                ]
            );

            SlideValue::updateOrCreate(
                [
                    'presentation_month_id' => $month->id,
                    'slide_id' => $slide->id,
                ],
                [
                    'values_json' => $this->defaultsForSlide((int) $slide->slide_number),
                    'updated_by' => $admin->id,
                ]
            );
        }
    }

    private function defaultsForSlide(int $slideNumber): array
    {
        return match ($slideNumber) {
            9 => [
                'title' => 'Əsas fəaliyyət göstəriciləri',
                'subtitle' => 'birbaşa funksional istiqamətlər üzrə icmal',
                'total_requests' => '3 379 088',
                'period' => '09.05.2019 - 28.02.2025',
                'number_1' => '2 824 592',
                'number_2' => '408 355',
                'number_3' => '92 589',
                'number_4' => '13 224',
                'number_5' => '38 328',
                'year_requests' => '126 991',
                'cum_requests' => '2 824 592',
                'baku' => '64 779',
                'regions' => '62 212',
                'avg_dailyreqs' => '4 188',
                'sat_year' => '98,4',
                'sat_total' => '98,5',
                'waiting_time' => '2:41',
                'service_time' => '7:28',
                'donut_colors' => [
                    'baku' => '#1f7eb4',
                    'regions' => '#20b878',
                ],
                'donut_block' => [
                    'width' => 190,
                    'height' => 190,
                    'baku_percent' => 51,
                    'regions_percent' => 49,
                    'baku_color' => '#1f7eb4',
                    'regions_color' => '#20b878',
                ],
            ],

            10 => [
                'title' => 'Əsas fəaliyyət göstəriciləri: dinamika, tendensiya və müqaisələr',
                'subtitle' => '',
                'number_1' => '',
                'number_2' => '',
                'number_3' => '',
                'donut_block' => [
                    'width' => 1080,
                    'height' => 275,
                    'legend' => [
                        ['label' => 'Sosial müdafiə', 'color' => '#10A8D8', 'order' => 1],
                        ['label' => 'Əlillik', 'color' => '#BDEEFF', 'order' => 2],
                        ['label' => 'Məşğulluq', 'color' => '#21BF73', 'order' => 3],
                        ['label' => 'VƏM', 'color' => '#0047BA', 'order' => 4],
                        ['label' => 'Əmək münasibətləri', 'color' => '#2C75FF', 'order' => 5],
                        ['label' => 'Arayışların verilməsi', 'color' => '#F4C542', 'order' => 6],
                    ],
                    'charts' => [
                        [
                            'center_label' => '2024',
                            'center_value' => '726 361',
                            'values' => [62.7, 0.1, 27.6, 5.8, 1.9, 1.9],
                        ],
                        [
                            'center_label' => '2025',
                            'center_value' => '126 991',
                            'values' => [60.1, 0.1, 20.6, 16.4, 1.8, 1.0],
                        ],
                        [
                            'center_label' => 'Ümumi',
                            'center_value' => '2 824 592',
                            'values' => [61.4, 0.8, 23.8, 9.5, 1.2, 3.3],
                        ],
                    ],
                ],
            ],

            default => [],
        };
    }
}
