<?php

namespace App\Services\Charts;

use Spatie\Browsershot\Browsershot;

class Slide8DiagramImageService
{
    public function renderSvg(array $data): string
    {
        $bars = $data['bars'] ?? [];
        $color = $data['color'] ?? '#0076B6';

        $width = (int)($data['width'] ?? 689);
        $height = (int)($data['height'] ?? 309);

        $top = 10;
        $bottom = 90;
        $left = 25;
        $right = 25;

        $chartHeight = $height - $top - $bottom;
        $barWidth = 74;
        $gap = 78;

        $svg = [];

        $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';
        $svg[] = '<rect width="100%" height="100%" fill="white"/>';

        for ($i = 0; $i <= 10; $i++) {
            $y = $top + ($chartHeight / 10) * $i;
            $svg[] = '<line x1="' . $left . '" y1="' . $y . '" x2="' . ($width - $right) . '" y2="' . $y . '" stroke="#E9EEF2" stroke-width="1"/>';
        }

        foreach ($bars as $index => $bar) {
            $valueRaw = str_replace(',', '.', (string)($bar['value'] ?? 0));
            $value = max(0, min(100, (float)$valueRaw));
            $label = (string)($bar['label'] ?? '');

            $x = $left + ($index * ($barWidth + $gap));
            $barHeight = ($value / 100) * $chartHeight;
            $y = $top + ($chartHeight - $barHeight);

            $svg[] = '<rect x="' . $x . '" y="' . $y . '" width="' . $barWidth . '" height="' . $barHeight . '" fill="' . $color . '"/>';

            $svg[] = '<text x="' . ($x + $barWidth / 2) . '" y="' . ($y + $barHeight / 2 + 6) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="24" font-weight="700" fill="white">'
                . round($value) . ' %</text>';

            $lines = $this->wrapLabel($label, 18);

            foreach ($lines as $lineIndex => $line) {
                $svg[] = '<text x="' . $x . '" y="' . ($height - 70 + ($lineIndex * 13)) . '" font-family="Arial, sans-serif" font-size="12" font-weight="600" fill="#19526B">'
                    . e($line) . '</text>';
            }
        }

        $svg[] = '</svg>';

        return implode("\n", $svg);
    }

    public function makeChartImage(string $name, array $data): string
    {
        $svg = $this->renderSvg($data);

        $dir = storage_path('app/charts');

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $svgPath = $dir . '/' . $name . '.svg';
        $pngPath = $dir . '/' . $name . '.png';

        file_put_contents($svgPath, $svg);

        Browsershot::html($svg)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->windowSize((int)($data['width'] ?? 900), (int)($data['height'] ?? 330))
            ->transparentBackground()
            ->save($pngPath);

        return $pngPath;
    }

    private function wrapLabel(string $text, int $limit): array
    {
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $line = '';

        foreach ($words as $word) {
            $test = trim($line . ' ' . $word);

            if (mb_strlen($test) > $limit && $line !== '') {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $test;
            }
        }

        if ($line !== '') {
            $lines[] = $line;
        }

        return array_slice($lines, 0, 5);
    }
}
