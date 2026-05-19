<?php

namespace App\Services\Charts;

class DonutChartSvg
{
    public function render(array $data): string
    {
        $width = 550;
        $height = 300;

        $cx = 160;
        $cy = 155;
        $outerR = 118;
        $innerR = 64;

        $totalLabel = $data['total_label'] ?? 'Ümumi';
        $totalValue = $data['total_value'] ?? '2 824 592';
        $items = $data['items'] ?? [];

        $sum = array_sum(array_column($items, 'value'));
        $startAngle = -95;

        $svg = [];

        $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';

        foreach ($items as $item) {
            $value = (float) $item['value'];
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
            $svg[] = $this->percentLabel($cx, $cy, $outerR + 18, $midAngle, $value);

            $startAngle = $endAngle;
        }

        // Center text
        $svg[] = '<text x="' . $cx . '" y="' . ($cy - 12) . '" text-anchor="middle" font-family="Arial" font-size="17" fill="#003A9B">'
            . $this->escape($totalLabel) .
            '</text>';
        $svg[] = '<text x="' . $cx . '" y="' . ($cy + 17) . '" text-anchor="middle" font-family="Arial" font-size="25" font-weight="700" fill="#003A9B">' . $this->escape($totalValue) . '</text>';

        // Legend
        $legendX1 = 345;
        $legendX2 = 450;
        $legendY = 70;
        $rowGap = 58;

        foreach ($items as $i => $item) {
            $colX = $i % 2 === 0 ? $legendX1 : $legendX2;
            $rowY = $legendY + intdiv($i, 2) * $rowGap;

            $svg[] = '<line x1="' . $colX . '" y1="' . $rowY . '" x2="' . ($colX + 40) . '" y2="' . $rowY . '" stroke="' . $item['color'] . '" stroke-width="5" stroke-linecap="round"/>';

            $labelLines = $this->wrapLegendLabel($item['label'], 18);

            $svg[] = '<text x="' . $colX . '" y="' . ($rowY + 17) . '" font-family="Arial" font-size="13" font-weight="600" fill="#111111">';

            foreach ($labelLines as $lineIndex => $line) {
                $dy = $lineIndex === 0 ? 0 : 18;

                $svg[] = '<tspan x="' . $colX . '" dy="' . $dy . '">'
                    . $this->escape($line)
                    . '</tspan>';
            }

            $svg[] = '</text>';
        }

        $svg[] = '</svg>';

        return implode("\n", $svg);
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
        float $value
    ): string {
        [$x, $y] = $this->polar($cx, $cy, $r, $angle);

        return '<text x="' . $x . '" y="' . $y . '" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-size="14" font-weight="700" fill="#26315E">'
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

    private function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function wrapLegendLabel(string $label): array
    {
        $words = preg_split('/\s+/', trim($label));

        if (!$words || count($words) === 1) {
            return [$label];
        }

        // если ровно 2 слова → всегда перенос
        if (count($words) === 2) {
            return [$words[0], $words[1]];
        }

        // если больше слов — балансируем
        $half = ceil(count($words) / 2);

        return [
            implode(' ', array_slice($words, 0, $half)),
            implode(' ', array_slice($words, $half)),
        ];
    }
}
