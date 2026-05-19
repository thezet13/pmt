<?php

namespace App\Services\Pptx\Slides;

use App\Services\Charts\Slide8DiagramImageService;
use App\Services\Pptx\PptxMediaReplacer;

class Slide8Renderer
{
    public function __construct(
        private Slide8DiagramImageService $diagramService,
        private PptxMediaReplacer $mediaReplacer,
    ) {}

    public function render(array $data, string $tmpDir, int $slideXmlNumber): void
    {
        $diagramBlock = $data['diagram_block'] ?? null;

        if (!is_array($diagramBlock)) {
            return;
        }

        $diagramPath = $this->diagramService->makeChartImage(
            'slide_8_diagram',
            $diagramBlock
        );

        $this->mediaReplacer->replaceImageByShapeName(
            $tmpDir,
            $slideXmlNumber,
            'slide_8_diagram',
            $diagramPath
        );
    }
}
