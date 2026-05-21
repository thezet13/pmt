<?php

namespace App\Services\Charts;

use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class Slide10DonutImageService
{
    public function makeChartImage(string $name, array $data): string
    {
        $svg = app(MultiDonutChartSvg::class)->render($data);

        $width = $data['width'] ?? 980;
        $height = $data['height'] ?? 300;

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

        $browsershot = Browsershot::html($html)
            ->noSandbox()
            ->windowSize($width, $height)
            ->deviceScaleFactor(2)
            ->setOption('omitBackground', true)
            ->transparentBackground();

        if (PHP_OS_FAMILY !== 'Windows') {
            $browsershot->setChromePath('/usr/bin/chromium');
        }
        $browsershot->save($fullPngPath);


        return $fullPngPath;
    }
}
