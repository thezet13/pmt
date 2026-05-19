<?php

namespace App\Services\Pptx\Slides;

use App\Services\Charts\Slide13DonutImageService;
use App\Services\Pptx\PptxMediaReplacer;

class Slide13Renderer
{
    public function render(array $data, string $tmpDir, int $slideXmlNumber): void
    {
        $donutData = $data['donut_cert'] ?? null;

        if (!is_array($donutData)) {
            return;
        }

        $chartPath = app(Slide13DonutImageService::class)
            ->makeChartImage('slide_13_donut', $donutData);

        app(PptxMediaReplacer::class)->replaceImageByShapeName(
            $tmpDir,
            $slideXmlNumber,
            'slide_13_donut',
            $chartPath
        );
    }
}
