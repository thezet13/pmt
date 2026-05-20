<?php

namespace App\Services\Charts;

use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class Slide9DonutImageService
{

    public function renderSvg(array $data): string
    {
        $width = (int) ($data['width'] ?? 190);
        $height = (int) ($data['height'] ?? 190);

        $bakuPercent = (int) ($data['baku_percent'] ?? 51);
        $regionsPercent = (int) ($data['regions_percent'] ?? 49);

        $bakuColor = $data['baku_color'] ?? '#1f7eb4';
        $regionsColor = $data['regions_color'] ?? '#20b878';

        $radius = 70;
        $stroke = 50;

        $circumference = 2 * pi() * $radius;

        $bakuLength = ($bakuPercent / 100) * $circumference;
        $regionsLength = ($regionsPercent / 100) * $circumference;

        return <<<SVG
<svg width="{$width}" height="{$height}" viewBox="0 0 190 190" xmlns="http://www.w3.org/2000/svg">

    <g transform="rotate(-90 95 95)">

        <circle
            cx="95"
            cy="95"
            r="{$radius}"
            fill="none"
            stroke="{$regionsColor}"
            stroke-width="{$stroke}"
            stroke-dasharray="{$bakuLength} {$circumference}"
            stroke-linecap="butt"
        />

        <circle
            cx="95"
            cy="95"
            r="{$radius}"
            fill="none"
            stroke="{$bakuColor}"
            stroke-width="{$stroke}"
            stroke-dasharray="{$regionsLength} {$circumference}"
            stroke-dashoffset="-{$bakuLength}"
            stroke-linecap="butt"
        />

    </g>

    <text x="25" y="100" text-anchor="middle" font-size="20" font-family="Arial" fill="#FFFFFF" font-weight="700">{$bakuPercent}%</text>
    <text x="165" y="100" text-anchor="middle" font-size="20" font-family="Arial" fill="#FFFFFF" font-weight="700">{$regionsPercent}%</text>

</svg>
SVG;
    }
    public function makeChartImage(string $name, array $data): string
    {
        $width = (int) ($data['width'] ?? 190);
        $height = (int) ($data['height'] ?? 190);

        $bakuPercent = (int) ($data['baku_percent'] ?? 51);
        $regionsPercent = (int) ($data['regions_percent'] ?? 49);

        $bakuColor = $data['baku_color'] ?? '#1f7eb4';
        $regionsColor = $data['regions_color'] ?? '#20b878';

        $radius = 70;
        $stroke = 50;

        $circumference = 2 * pi() * $radius;

        $bakuLength = ($bakuPercent / 100) * $circumference;
        $regionsLength = ($regionsPercent / 100) * $circumference;

        $svg = $this->renderSvg($data);

        $svgPath = "charts/{$name}.svg";
        $pngPath = "charts/{$name}.png";

        Storage::disk('local')->put($svgPath, $svg);

        $fullPngPath = Storage::disk('local')->path($pngPath);

        $html = '
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
html, body {
    margin: 0;
    padding: 0;
    background: transparent;
    width: ' . $width . 'px;
    height: ' . $height . 'px;
    overflow: hidden;
}
svg {
    display: block;
}
</style>
</head>
<body>
' . $svg . '
</body>
</html>';

        Browsershot::html($html)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->windowSize($width, $height)
            ->deviceScaleFactor(2)
            ->setOption('omitBackground', true)
            ->transparentBackground()
            ->save($fullPngPath);

        return $fullPngPath;
    }
}
