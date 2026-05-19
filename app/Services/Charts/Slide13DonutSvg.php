<?php

namespace App\Services\Charts;

class Slide13DonutSvg
{
    public function render(array $data): string
    {
        $width = (int) ($data['width'] ?? 410);
        $height = (int) ($data['height'] ?? 470);

        $legend = $data['legend'] ?? [];
        $values = $data['values'] ?? [];

        $items = $this->mergeLegendWithValues($legend, $values);
        $total = array_sum(array_column($items, 'value'));

        $svg = [];
        $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';

        $this->renderDonut($svg, 205, 155, 128, 60, $items, $total);
        $this->renderLegend($svg, $items, 100, 300, 22);

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
        float $total
    ): void {
        if ($total <= 0) {
            return;
        }

        $startAngle = -90;

        foreach ($items as $item) {
            $value = (float) $item['value'];

            if ($value <= 0) {
                continue;
            }

            $angle = ($value / $total) * 360;
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

            $percent = round(($value / $total) * 100);

            $midAngle = $startAngle + ($angle / 2);

            if ($percent >= 6) {
                $labelR = ($outerR + $innerR) / 2;

                $svg[] = $this->percentLabel(
                    $cx,
                    $cy,
                    $labelR,
                    $midAngle,
                    $percent,
                    '#ffffff'
                );
            } else {
                $svg[] = $this->outsidePercentLabel(
                    $cx,
                    $cy,
                    $outerR,
                    $midAngle,
                    $percent,
                    $item['color']
                );
            }

            $startAngle = $endAngle;
        }

        $svg[] = '<text x="' . $cx . '" y="' . ($cy - 25) . '" text-anchor="middle" font-family="Arial" font-size="20" font-weight="600" fill="#283A78">Cəmi</text>';

        $svg[] = '<text x="' . $cx . '" y="' . ($cy + 5) . '" text-anchor="middle" font-family="Arial" font-size="28" font-weight="700" fill="#d50ac1">'
            . $this->escape((string) $total)
            . '</text>';
        $svg[] = '<text x="' . $cx . '" y="' . ($cy + 30) . '" text-anchor="middle" font-family="Arial" font-size="20" font-weight="600" fill="#283A78">sertifikat</text>';
    }


    private function outsidePercentLabel(
        float $cx,
        float $cy,
        float $outerR,
        float $angle,
        float $percent,
        string $color
    ): string {
        [$x, $y] = $this->polar($cx, $cy, $outerR + 18, $angle);

        return '
        <text
            x="' . $x . '"
            y="' . ($y + 4) . '"
            text-anchor="middle"
            font-family="Arial"
            font-size="12"
            font-weight="700"
            fill="' . $color . '">
            ' . $percent . '%
        </text>';
    }


    private function renderLegend(
        array &$svg,
        array $items,
        int $x,
        int $startY,
        int $rowGap
    ): void {
        foreach ($items as $i => $item) {
            $y = $startY + ($i * $rowGap);

            $svg[] = '<circle cx="' . $x . '" cy="' . $y . '" r="6" fill="' . $item['color'] . '"/>';

            $percent = $item['total'] > 0
                ? round(($item['value'] / $item['total']) * 100)
                : 0;

            $svg[] = '
<text
    x="' . ($x + 16) . '"
    y="' . ($y + 5) . '"
    font-family="Arial"
    fill="#202020">

    <tspan
        font-size="11"
        font-weight="600">
        ' . $this->escape($item['label']) . ' —
    </tspan>

    <tspan
        font-size="15"
        font-weight="700"
        fill="' . $item['color'] . '">
         ' . $item['value'] . '
    </tspan>

    <tspan
        font-size="11"
        font-weight="600"
        fill="#666666">
        / ' . $percent . '%
    </tspan>

</text>';
        }
    }

    private function mergeLegendWithValues(array $legend, array $values): array
    {
        $items = [];
        $total = array_sum(array_map('floatval', $values));

        foreach ($legend as $i => $legendItem) {
            $items[] = [
                'label' => $legendItem['label'] ?? '',
                'color' => $legendItem['color'] ?? '#999999',
                'value' => (float) ($values[$i] ?? 0),
                'total' => $total,
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
        float $percent,
        string $color
    ): string {
        [$x, $y] = $this->polar($cx, $cy, $r, $angle);

        return '<text x="' . $x . '" y="' . ($y + 5) . '" text-anchor="middle" font-family="Arial" font-size="13" font-weight="700" fill="' . $color . '">'
            . $percent . '%
        </text>';
    }

    private function polar(float $cx, float $cy, float $r, float $angle): array
    {
        $rad = deg2rad($angle);

        return [
            $cx + ($r * cos($rad)),
            $cy + ($r * sin($rad)),
        ];
    }

    private function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
