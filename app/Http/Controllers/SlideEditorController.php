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

        $values = array_replace_recursive(
            $this->getDefaultsForSlide((int) $slide->slide_number),
            $values
        );

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

        if (
            !$user->isAdmin()
            && !$user->allowedSlides()->where('slides.id', $slide->id)->exists()
        ) {
            abort(403);
        }

        $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'status' => ['required', 'in:in_progress,completed'],
        ]);

        $slideNumber = (int) $slide->slide_number;

        $valuesJson = match ($slideNumber) {
            4 => $this->extractSlide4Values($request),
            8 => $this->extractSlide8Values($request),
            9 => $this->extractSlide9Values($request),
            10 => $this->extractSlide10Values($request),
            13 => $this->extractSlide13Values($request),

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
            8 => $this->getSlide8Defaults(),
            9 => $this->getSlide9Defaults(),
            10 => $this->getSlide10Defaults(),
            13 => $this->getSlide13Defaults(),
            default => [],
        };
    }

    private function getSlide4Defaults(): array
    {
        return [
            'title' => 'Maliyyə fəaliyyətinin icmalı',

            'subtitle' => 'xərclər smetası və icrası ilə bağlı vəziyyət',

            'text_blocks' => [],

            'budget_rows' => [],

            'note' => 'Agentlik, “DOST” mərkəzləri və Agentliyin təsis etdiyi təsərrüfat cəmiyyətləri üzrə',
        ];
    }


    private function getSlide8Defaults(): array
    {
        return [
            'title' => '',
            'subtitle' => '',

            'tedfaiz' => '',
            'meqfaiz' => '',

            'tedbir' => 0,
            'tr' => 0,
            'qr' => 0,
            'tf' => 0,
            'qf' => 0,

            'icx' => '',

            'sm' => 0,
            'pi' => 0,
            'iti' => 0,
            'iqi' => 0,

            'diagram_block' => [
                'width' => 900,
                'height' => 330,

                'color' => '#0076B6',

                'bars' => []
            ],
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

            'donut_block' => [
                'width' => 1080,
                'height' => 275,

                'legend' => [],

                'charts' => [],
            ],
        ];
    }

    private function getSlide13Defaults(): array
    {
        return [
            'title' => '',
            'stat' => '',

            'iseqebil' => '32',
            'isdenazad' => '12',
            'orta_isci' => '831',
            'turnover' => '1,4%',

            'umimi' => '836',
            'ortayas' => '36',
            'kisi' => '46',
            'qadin' => '54',

            'v_ag' => '239',
            'v_ap' => '81',
            'v_me' => '158',
            'v_mmc' => '113',

            'i_ag' => '836',
            'i_ap' => '231',
            'i_me' => '605',
            'i_mmc' => '758',

            's_ag' => '1 075',
            's_ap' => '312',
            's_me' => '763',
            's_mmc' => '871',

            'qeyd' => '',

            'donut_cert' => [
                'width' => 410,
                'height' => 470,
                'legend' => [],
                'values' => [],
            ],
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



    private function extractSlide8Values(Request $request): array
    {
        return [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),

            'tedfaiz' => $request->input('tedfaiz'),
            'meqfaiz' => $request->input('meqfaiz'),

            'tedbir' => $request->input('tedbir'),
            'tr' => $request->input('tr'),
            'qr' => $request->input('qr'),
            'tf' => $request->input('tf'),
            'qf' => $request->input('qf'),

            'icx' => $request->input('icx'),

            'sm' => $request->input('sm'),
            'pi' => $request->input('pi'),
            'iti' => $request->input('iti'),
            'iqi' => $request->input('iqi'),

            'diagram_block' => [
                'width' => 900,
                'height' => 330,

                'color' => '#0076B6',

                'bars' => collect($request->input('diagram_block.bars', []))
                    ->map(fn($bar) => [
                        'label' => (string) ($bar['label'] ?? ''),
                        'value' => (string) ($bar['value'] ?? 0),
                    ])
                    ->values()
                    ->all(),
            ],
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

        $values['donut_block'] = $request->input('donut_block', []);

        return $values;
    }


    private function extractSlide13Values(Request $request): array
    {
        $keys = [
            'title',
            'stat',

            'iseqebil',
            'isdenazad',
            'orta_isci',
            'turnover',

            'umimi',
            'ortayas',
            'kisi',
            'qadin',

            'v_ag',
            'v_ap',
            'v_me',
            'v_mmc',

            'i_ag',
            'i_ap',
            'i_me',
            'i_mmc',

            's_ag',
            's_ap',
            's_me',
            's_mmc',

            'qeyd',
        ];

        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $request->input($key);
        }

        $values['donut_cert'] = $request->input('donut_cert', []);

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
