<?php

namespace App\Services\Pptx\Slides;

class Slide15Renderer
{
    public function render(array $data, string $tmpDir, int $slideXmlNumber): void
    {
        $slideXmlPath = $tmpDir . "/ppt/slides/slide{$slideXmlNumber}.xml";

        if (!file_exists($slideXmlPath)) {
            return;
        }

        $xml = file_get_contents($slideXmlPath);

        $baseY = 1545386;
        $blockGap = 120000;

        $lineHeight = 170000;
        $minTextHeight = 220000;
        $headingHeight = 220000;

        $currentY = $baseY;

        for ($i = 1; $i <= 6; $i++) {
            $text = (string)($data["text{$i}"] ?? '');

            $lines = $this->estimateLines($text, 55);
            $textHeight = max($minTextHeight, $lines * $lineHeight);

            $this->moveGroupContainingShape($xml, "slide_15_item_{$i}_heading", $currentY);

            $currentY += $headingHeight + $textHeight + $blockGap;
        }

        file_put_contents($slideXmlPath, $xml);
    }

    private function estimateLines(string $text, int $charsPerLine): int
    {
        $text = trim(strip_tags($text));

        if ($text === '') {
            return 1;
        }

        $paragraphs = preg_split('/\R/u', $text) ?: [$text];

        $lines = 0;

        foreach ($paragraphs as $paragraph) {
            $length = mb_strlen(trim($paragraph));
            $lines += max(1, (int) ceil($length / $charsPerLine));
        }

        return max(1, $lines);
    }

    private function moveGroupContainingShape(string &$xml, string $shapeName, int $newGroupY): void
    {
        $pos = strpos($xml, 'name="' . $shapeName . '"');

        if ($pos === false) {
            logger()->warning("Slide15 shape not found: {$shapeName}");
            return;
        }

        $groupStart = strrpos(substr($xml, 0, $pos), '<p:grpSp>');

        if ($groupStart === false) {
            logger()->warning("Slide15 group start not found for: {$shapeName}");
            return;
        }

        $groupEnd = strpos($xml, '</p:grpSp>', $pos);

        if ($groupEnd === false) {
            logger()->warning("Slide15 group end not found for: {$shapeName}");
            return;
        }

        $groupEnd += strlen('</p:grpSp>');

        $before = substr($xml, 0, $groupStart);
        $groupXml = substr($xml, $groupStart, $groupEnd - $groupStart);
        $after = substr($xml, $groupEnd);

        $groupXml = preg_replace(
            '/(<p:grpSpPr>\s*<a:xfrm>\s*<a:off\s+x="[^"]+"\s+y=")([^"]+)(")/',
            '${1}' . $newGroupY . '${3}',
            $groupXml,
            1
        );

        $xml = $before . $groupXml . $after;
    }
}
