<?php

namespace App\Services\Charts;

class MultiDonutChartSvg
{
    public function render(array $data): string
    {
        $width = 1080;
        $height = 275;

        $outerR = 118;
        $innerR = 64;

        $legend = $data['legend'] ?? [];
        $charts = $data['charts'] ?? [];

        $chartCenters = [
            ['x' => 125, 'y' => 155],
            ['x' => 400, 'y' => 155],
            ['x' => 675, 'y' => 155],
        ];

        $svg = [];
        $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';

        foreach ($charts as $chartIndex => $chart) {
            if (!isset($chartCenters[$chartIndex])) {
                continue;
            }

            $cx = $chartCenters[$chartIndex]['x'];
            $cy = $chartCenters[$chartIndex]['y'];

            $items = $this->mergeLegendWithValues($legend, $chart['values'] ?? []);

            $this->renderDonut(
                $svg,
                $cx,
                $cy,
                $outerR,
                $innerR,
                $items,
                $chart['center_label'] ?? '',
                $chart['center_value'] ?? ''
            );
        }

        $this->renderLegend($svg, $legend, 845, 960, 82, 58);

        $svg[] = '</svg>';

        return implode("\n", $svg);
    }

    private function renderDonut(
        array &$svg,
        float $cx,
        float $cy,
        float $outerR,
        float $innerR,
        array $items,
        string $centerLabel,
        string $centerValue
    ): void {
        $sum = array_sum(array_column($items, 'value'));

        if ($sum <= 0) {
            return;
        }

        $startAngle = -95;

        foreach ($items as $item) {
            $value = (float) $item['value'];

            if ($value <= 0) {
                continue;
            }

            $angle = ($value / $sum) * 360;
            $endAngle = $startAngle + $angle;

            $svg[] = $this->donutSegment(
                $cx,
                $cy,
                $outerR,
                $innerR,
                $startAngle,
                $endAngle,
                $item['color']
            );

            $midAngle = $startAngle + ($angle / 2);

            //$svg[] = $this->percentLabel($cx, $cy, $outerR + 16, $midAngle, $value);
            if ($value >= 8) {
                $labelR = ($outerR + $innerR) / 2;

                $svg[] = $this->percentLabel(
                    $cx,
                    $cy,
                    $labelR,
                    $midAngle,
                    $value,
                    '#ffffff'
                );
            } else {
                $svg[] = $this->percentLabel(
                    $cx,
                    $cy,
                    $outerR + 16,
                    $midAngle,
                    $value,
                    '#26315E'
                );
            }

            $startAngle = $endAngle;
        }

        $svg[] = '<text x="' . $cx . '" y="' . ($cy - 12) . '" text-anchor="middle" font-family="Arial" font-size="17" fill="#003A9B">'
            . $this->escape($centerLabel)
            . '</text>';

        $svg[] = '<text x="' . $cx . '" y="' . ($cy + 18) . '" text-anchor="middle" font-family="Arial" font-size="25" font-weight="700" fill="#003A9B">'
            . $this->escape($centerValue)
            . '</text>';
    }

    private function renderLegend(
        array &$svg,
        array $legend,
        int $legendX1,
        int $legendX2,
        int $legendY,
        int $rowGap
    ): void {
        foreach ($legend as $i => $item) {
            $colX = $i % 2 === 0 ? $legendX1 : $legendX2;
            $rowY = $legendY + intdiv($i, 2) * $rowGap;

            $svg[] = '<line x1="' . $colX . '" y1="' . $rowY . '" x2="' . ($colX + 40) . '" y2="' . $rowY . '" stroke="' . $item['color'] . '" stroke-width="5" stroke-linecap="round"/>';

            $labelLines = $this->wrapLegendLabel($item['label']);

            $svg[] = '<text x="' . $colX . '" y="' . ($rowY + 17) . '" font-family="Arial" font-size="13" font-weight="600" fill="#111111">';

            foreach ($labelLines as $lineIndex => $line) {
                $dy = $lineIndex === 0 ? 0 : 18;

                $svg[] = '<tspan x="' . $colX . '" dy="' . $dy . '">'
                    . $this->escape($line)
                    . '</tspan>';
            }

            $svg[] = '</text>';
        }
    }

    private function mergeLegendWithValues(array $legend, array $values): array
    {
        $items = [];

        foreach ($legend as $i => $legendItem) {
            $items[] = [
                'label' => $legendItem['label'],
                'color' => $legendItem['color'],
                'value' => (float) ($values[$i] ?? 0),
                'order' => (int) ($legendItem['order'] ?? ($i + 1)),
            ];
        }

        usort($items, fn($a, $b) => $a['order'] <=> $b['order']);

        return $items;
    }

    private function donutSegment(
        float $cx,
        float $cy,
        float $outerR,
        float $innerR,
        float $startAngle,
        float $endAngle,
        string $color
    ): string {
        [$x1, $y1] = $this->polar($cx, $cy, $outerR, $startAngle);
        [$x2, $y2] = $this->polar($cx, $cy, $outerR, $endAngle);

        [$x3, $y3] = $this->polar($cx, $cy, $innerR, $endAngle);
        [$x4, $y4] = $this->polar($cx, $cy, $innerR, $startAngle);

        $largeArc = ($endAngle - $startAngle) > 180 ? 1 : 0;

        $d = "
            M $x1 $y1
            A $outerR $outerR 0 $largeArc 1 $x2 $y2
            L $x3 $y3
            A $innerR $innerR 0 $largeArc 0 $x4 $y4
            Z
        ";

        return '<path d="' . $d . '" fill="' . $color . '" stroke="#ffffff" stroke-width="2"/>';
    }

    private function percentLabel(
        float $cx,
        float $cy,
        float $r,
        float $angle,
        float $value,
        string $color = '#26315E'
    ): string {
        [$x, $y] = $this->polar($cx, $cy, $r, $angle);

        return '<text x="' . $x . '" y="' . $y . '" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-size="13" font-weight="700" fill="' . $color . '">'
            . number_format($value, 1)
            . '%</text>';
    }

    private function polar(float $cx, float $cy, float $r, float $angle): array
    {
        $rad = deg2rad($angle);

        return [
            round($cx + $r * cos($rad), 3),
            round($cy + $r * sin($rad), 3),
        ];
    }

    private function wrapLegendLabel(string $label): array
    {
        $words = preg_split('/\s+/', trim($label));

        if (!$words || count($words) === 1) {
            return [$label];
        }

        if (count($words) === 2) {
            return [$words[0], $words[1]];
        }

        $half = ceil(count($words) / 2);

        return [
            implode(' ', array_slice($words, 0, $half)),
            implode(' ', array_slice($words, $half)),
        ];
    }

    private function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
