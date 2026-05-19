<?php

namespace App\Http\Controllers;

use App\Models\PresentationMonth;
use App\Models\Slide;
use App\Models\SlideStatus;
use App\Models\SlideValue;
use Illuminate\Http\Request;

class SlideEditorController extends Controller
{


    private function toNumber(mixed $value): int
    {
        return (int) preg_replace('/[^\d]/', '', (string) $value);
    }

    public function edit(Request $request, Slide $slide)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->allowedSlides()->where('slides.id', $slide->id)->exists()) {
            abort(403);
        }

        $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

        $slideValue = SlideValue::firstOrCreate([
            'presentation_month_id' => $activeMonth->id,
            'slide_id' => $slide->id,
        ], [
            'values_json' => [],
        ]);

        $slideStatus = SlideStatus::firstOrCreate([
            'presentation_month_id' => $activeMonth->id,
            'slide_id' => $slide->id,
        ], [
            'status' => 'in_progress',
        ]);

        $values = $slideValue->values_json ?? [];
        $values = array_merge($this->getDefaultsForSlide((int) $slide->slide_number), $values);

        return view('slides.edit', compact(
            'slide',
            'activeMonth',
            'slideValue',
            'slideStatus',
            'values'
        ));
    }

    public function update(Request $request, Slide $slide)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->allowedSlides()->where('slides.id', $slide->id)->exists()) {
            abort(403);
        }

        $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'status' => ['required', 'in:in_progress,completed'],

            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],

            'total_requests' => ['nullable', 'string', 'max:255'],
            'period' => ['nullable', 'string', 'max:255'],

            'number_1' => ['nullable', 'string', 'max:255'],
            'number_2' => ['nullable', 'string', 'max:255'],
            'number_3' => ['nullable', 'string', 'max:255'],
            'number_4' => ['nullable', 'string', 'max:255'],
            'number_5' => ['nullable', 'string', 'max:255'],

            'year_requests' => ['nullable', 'string', 'max:255'],
            'cum_requests' => ['nullable', 'string', 'max:255'],

            'baku' => ['nullable', 'string', 'max:255'],
            'regions' => ['nullable', 'string', 'max:255'],

            'avg_dailyreqs' => ['nullable', 'string', 'max:255'],
            'sat_year' => ['nullable', 'string', 'max:255'],
            'sat_total' => ['nullable', 'string', 'max:255'],

            'waiting_time' => ['nullable', 'string', 'max:255'],
            'service_time' => ['nullable', 'string', 'max:255'],

            'donut_block' => ['nullable', 'array'],
            'donut_colors' => ['nullable', 'array'],
        ]);

        $slideNumber = (int) $slide->slide_number;

        $valuesJson = match ($slideNumber) {
            4 => $this->extractSlide4Values($request),
            9 => $this->extractSlide9Values($request),
            10 => $this->extractSlide10Values($request),
            default => $this->extractSimpleSlideValues($request),
        };

        SlideValue::updateOrCreate([
            'presentation_month_id' => $activeMonth->id,
            'slide_id' => $slide->id,
        ], [
            'values_json' => $valuesJson,
            'updated_by' => $user->id,
        ]);

        SlideStatus::updateOrCreate([
            'presentation_month_id' => $activeMonth->id,
            'slide_id' => $slide->id,
        ], [
            'status' => $data['status'],
            'completed_by' => $data['status'] === 'completed' ? $user->id : null,
            'completed_at' => $data['status'] === 'completed' ? now() : null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Slide updated.',
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Slide updated.');
    }

    private function getDefaultsForSlide(int $slideNumber): array
    {
        return match ($slideNumber) {
            4 => $this->getSlide4Defaults(),
            9 => $this->getSlide9Defaults(),
            10 => $this->getSlide10Defaults(),
            default => [],
        };
    }

    private function getSlide4Defaults(): array
    {
        return [
            'title' => 'Maliyyə fəaliyyətinin icmalı',

            'subtitle' => 'xərclər smetası və icrası ilə bağlı vəziyyət',

            'text_blocks' => [
                '',
                '',
                '',
                '',
            ],

            'budget_rows' => [
                [
                    'label' => 'Əməyin ödənişi',
                    'smeta_amount' => '79 682 370,06',
                    'fact_amount' => '79 682 370,06',
                ],

                [
                    'label' => 'Malların (iş və xidmətlərin) satınalınması',
                    'smeta_amount' => '12 397 132,17',
                    'fact_amount' => '12 397 132,17',
                ],

                [
                    'label' => 'Sosial ödənişlər',
                    'smeta_amount' => '365 062,98',
                    'fact_amount' => '365 062,98',
                ],

                [
                    'label' => 'Qeyri-maliyyə aktivləri (tikinti, əsaslı təmir, qeyri-maliyyə aktivlər və s.)',
                    'smeta_amount' => '44 564 709,25',
                    'fact_amount' => '44 564 709,25',
                ],
            ],

            'note' => 'Agentlik, “DOST” mərkəzləri və Agentliyin təsis etdiyi təsərrüfat cəmiyyətləri üzrə',
        ];
    }

    private function getSlide9Defaults(): array
    {
        return [
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
        ];
    }

    private function getSlide10Defaults(): array
    {
        return [
            'title' => '',
            'subtitle' => '',
            'number_1' => '',
            'number_2' => '',
            'number_3' => '',
            'donut_block' => [],
        ];
    }


    private function extractSlide4Values(Request $request): array
    {
        return [
            'title' => $request->input('title', ''),
            'subtitle' => $request->input('subtitle', ''),
            'text_blocks' => $request->input('text_blocks', []),
            'budget_rows' => $request->input('budget_rows', []),
            'note' => $request->input('note', ''),
        ];
    }

    private function extractSlide9Values(Request $request): array
    {
        $baku = $this->toNumber($request->input('baku', 0));
        $regions = $this->toNumber($request->input('regions', 0));

        $total = $baku + $regions;

        $bakuPct = $total > 0
            ? round(($baku / $total) * 100)
            : 0;

        $regionsPct = $total > 0
            ? 100 - $bakuPct
            : 0;

        return [
            'title' => $request->input('title', ''),
            'subtitle' => $request->input('subtitle', ''),
            'total_requests' => $request->input('total_requests', ''),
            'period' => $request->input('period', ''),

            'number_1' => $request->input('number_1', ''),
            'number_2' => $request->input('number_2', ''),
            'number_3' => $request->input('number_3', ''),
            'number_4' => $request->input('number_4', ''),
            'number_5' => $request->input('number_5', ''),

            'year_requests' => $request->input('year_requests', ''),
            'cum_requests' => $request->input('cum_requests', ''),

            'baku' => $request->input('baku', ''),
            'regions' => $request->input('regions', ''),

            'avg_dailyreqs' => $request->input('avg_dailyreqs', ''),
            'sat_year' => $request->input('sat_year', ''),
            'sat_total' => $request->input('sat_total', ''),

            'waiting_time' => $request->input('waiting_time', ''),
            'service_time' => $request->input('service_time', ''),

            'donut_colors' => [
                'baku' => $request->input('donut_colors.baku', '#1f7eb4'),
                'regions' => $request->input('donut_colors.regions', '#20b878'),
            ],

            'donut_block' => [
                'width' => 190,
                'height' => 190,

                'baku_percent' => $bakuPct,
                'regions_percent' => $regionsPct,

                'baku_color' => $request->input('donut_colors.baku', '#1f7eb4'),
                'regions_color' => $request->input('donut_colors.regions', '#20b878'),
            ],
        ];
    }

    private function extractSlide10Values(Request $request): array
    {
        $values = [
            'title' => $request->input('title', ''),
            'subtitle' => $request->input('subtitle', ''),
            'number_1' => $request->input('number_1', ''),
            'number_2' => $request->input('number_2', ''),
            'number_3' => $request->input('number_3', ''),
        ];

        if ($request->has('donut_block')) {
            $values['donut_block'] = $request->input('donut_block');
        }

        return $values;
    }

    private function extractSimpleSlideValues(Request $request): array
    {
        return [
            'title' => $request->input('title', ''),
            'subtitle' => $request->input('subtitle', ''),
        ];
    }
}
