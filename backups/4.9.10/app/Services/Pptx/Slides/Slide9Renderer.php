<?php

namespace App\Services\Pptx\Slides;

use App\Services\Charts\Slide9DonutImageService;
use App\Services\Pptx\PptxMediaReplacer;

class Slide9Renderer
{
    public function render(array $data, string $tmpDir, int $slideXmlNumber): void
    {
        $donutData = $data['donut_block'] ?? null;

        if (!is_array($donutData)) {
            return;
        }

        $chartPath = app(Slide9DonutImageService::class)
            ->makeChartImage('slide_9_donut', $donutData);

        app(PptxMediaReplacer::class)->replaceImageByShapeName(
            $tmpDir,
            $slideXmlNumber,
            'slide_9_donut',
            $chartPath
        );
    }
}
