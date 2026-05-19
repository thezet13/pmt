<?php

namespace App\Services\Pptx\Slides;

use App\Services\Charts\Slide10DonutImageService;
use App\Services\Pptx\PptxMediaReplacer;

class Slide10Renderer
{
    public function render(array $data, string $tmpDir, int $slideXmlNumber): void
    {
        $donutData = $data['donut_block'] ?? null;

        if (!is_array($donutData)) {
            return;
        }

        $chartPath = app(Slide10DonutImageService::class)
            ->makeChartImage('slide_10_donuts', $donutData);

        app(PptxMediaReplacer::class)->replaceImageByShapeName(
            $tmpDir,
            $slideXmlNumber,
            'slide_10_donuts',
            $chartPath
        );
    }
}
