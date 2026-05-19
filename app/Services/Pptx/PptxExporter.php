<?php

namespace App\Services\Pptx;

use App\Models\PresentationMonth;
use App\Models\SlideValue;
use App\Models\Slide;
use ZipArchive;

class PptxExporter
{



    public function export(PresentationMonth $month): string
    {
        $templatePath = storage_path('app/pptx/templates/Monthly_report.pptx');

        $tmpDir = storage_path('app/pptx/tmp/' . uniqid());
        $exportPath = storage_path('app/pptx/exports/report_' . $month->year . '_' . $month->month . '.pptx');

        mkdir($tmpDir, 0777, true);

        $zip = new ZipArchive();
        $zip->open($templatePath);
        $zip->extractTo($tmpDir);
        $zip->close();

        $values = SlideValue::with('slide')
            ->where('presentation_month_id', $month->id)
            ->get();

        $slideFiles = glob($tmpDir . '/ppt/slides/slide*.xml');

        foreach ($values as $slideValue) {

            $slideNumber = $slideValue->slide->slide_number;
            $data = $slideValue->values_json ?? [];

            $replacements = $this->makeTextReplacements((int) $slideNumber, $data);

            foreach ($slideFiles as $slideFile) {
                $xml = file_get_contents($slideFile);
                $xml = $this->replaceSplitPlaceholders($xml, $replacements);
                file_put_contents($slideFile, $xml);
            }

            $this->renderCustomSlideParts(
                (int) $slideNumber,
                $data,
                $tmpDir,
                (int) $slideNumber
            );
        }

        $this->zipFolder($tmpDir, $exportPath);

        return $exportPath;
    }

    private function replaceSplitPlaceholders(string $xml, array $replacements): string
    {
        return preg_replace_callback(
            '/(<a:r\b[^>]*>.*?<\/a:r>)+/s',
            function ($matches) use ($replacements) {
                $runBlock = $matches[0];

                preg_match_all('/<a:t>(.*?)<\/a:t>/s', $runBlock, $textMatches);

                if (empty($textMatches[1])) {
                    return $runBlock;
                }

                $plainText = implode('', $textMatches[1]);
                $newText = strtr($plainText, $replacements);

                if ($newText === $plainText) {
                    return $runBlock;
                }

                $usedFirstTextNode = false;

                return preg_replace_callback(
                    '/<a:t>.*?<\/a:t>/s',
                    function () use (&$usedFirstTextNode, $newText) {
                        if (!$usedFirstTextNode) {
                            $usedFirstTextNode = true;

                            return '<a:t>' . htmlspecialchars($newText, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</a:t>';
                        }

                        return '<a:t></a:t>';
                    },
                    $runBlock
                );
            },
            $xml
        );
    }

    private function zipFolder(string $source, string $destination): void
    {
        $zip = new ZipArchive();
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    private function renderCustomSlideParts(
        int $slideNumber,
        array $data,
        string $tmpDir,
        int $slideXmlNumber
    ): void {
        $renderers = [
            8 => \App\Services\Pptx\Slides\Slide8Renderer::class,
            9 => \App\Services\Pptx\Slides\Slide9Renderer::class,
            10 => \App\Services\Pptx\Slides\Slide10Renderer::class,
            13 => \App\Services\Pptx\Slides\Slide13Renderer::class,
        ];

        if (!isset($renderers[$slideNumber])) {
            return;
        }

        app($renderers[$slideNumber])->render($data, $tmpDir, $slideXmlNumber);
    }

    public function exportSingleSlide(PresentationMonth $month, Slide $slide): string
    {
        $templatePath = storage_path('app/pptx/templates/slides/slide_' . $slide->slide_number . '.pptx');

        if (!file_exists($templatePath)) {
            abort(404, 'Slide template not found.');
        }

        $tmpDir = storage_path('app/pptx/tmp/' . uniqid());
        $exportPath = storage_path(
            'app/pptx/exports/slide_' . $slide->slide_number . '_' . $month->year . '_' . $month->month . '.pptx'
        );

        mkdir($tmpDir, 0777, true);

        $zip = new ZipArchive();
        $zip->open($templatePath);
        $zip->extractTo($tmpDir);
        $zip->close();

        $slideValue = SlideValue::where('presentation_month_id', $month->id)
            ->where('slide_id', $slide->id)
            ->first();

        $data = $slideValue?->values_json ?? [];

        $replacements = $this->makeTextReplacements((int) $slide->slide_number, $data);

        $slideFiles = glob($tmpDir . '/ppt/slides/slide*.xml');

        foreach ($slideFiles as $slideFile) {
            $xml = file_get_contents($slideFile);
            $xml = $this->replaceSplitPlaceholders($xml, $replacements);
            file_put_contents($slideFile, $xml);
        }

        // 2. Donut block image replacement
        $this->renderCustomSlideParts(
            (int) $slide->slide_number,
            $data,
            $tmpDir,
            1
        );

        $this->zipFolder($tmpDir, $exportPath);

        return $exportPath;
    }

    private function makeTextReplacements(int $slideNumber, array $data): array
    {
        $replacements = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $replacements["{{$slideNumber}_{$key}}"] = (string) $value;
        }

        if ($slideNumber === 4) {
            $replacements = array_merge(
                $replacements,
                $this->makeSlide4Replacements($data)
            );
        }

        return $replacements;
    }

    private function makeSlide4Replacements(array $data): array
    {
        $rows = $data['budget_rows'] ?? [];
        $texts = $data['text_blocks'] ?? [];

        if (!is_array($rows)) {
            $rows = [];
        }

        if (!is_array($texts)) {
            $texts = [];
        }

        $smetaTotal = 0;
        $factTotal = 0;

        foreach ($rows as $row) {
            $smetaTotal += $this->parseAmount($row['smeta_amount'] ?? 0);
            $factTotal += $this->parseAmount($row['fact_amount'] ?? 0);
        }

        $replacements = [
            '{4_text_1}' => (string) ($texts[0] ?? ''),
            '{4_text_2}' => (string) ($texts[1] ?? ''),
            '{4_text_3}' => (string) ($texts[2] ?? ''),
            '{4_text_4}' => (string) ($texts[3] ?? ''),

            '{4_qeyd}' => (string) ($data['note'] ?? ''),

            '{4_s_t}' => $this->formatAmount($smetaTotal),
            '{4_f_t}' => $this->formatAmount($factTotal),
            '{4_s_tp}' => $smetaTotal > 0 ? '100,00%' : '0,00%',
            '{4_f_tp}' => $smetaTotal > 0
                ? $this->formatPercent(($factTotal / $smetaTotal) * 100)
                : '0,00%',
        ];

        for ($i = 0; $i < 4; $i++) {
            $row = $rows[$i] ?? [];

            $smetaAmount = $this->parseAmount($row['smeta_amount'] ?? 0);
            $factAmount = $this->parseAmount($row['fact_amount'] ?? 0);

            $smetaPercent = $smetaTotal > 0 ? ($smetaAmount / $smetaTotal) * 100 : 0;
            $factPercent = $smetaAmount > 0 ? ($factAmount / $smetaAmount) * 100 : 0;
            $n = $i + 1;

            $replacements["{4_cat{$n}}"] = (string) ($row['label'] ?? '');
            $replacements["{4_smeta{$n}}"] = $this->formatAmount($smetaAmount);
            $replacements["{4_fact{$n}}"] = $this->formatAmount($factAmount);
            $replacements["{4_s_p{$n}}"] = $this->formatPercent($smetaPercent);
            $replacements["{4_f_p{$n}}"] = $this->formatPercent($factPercent);
        }

        return $replacements;
    }

    private function parseAmount(mixed $value): float
    {
        $value = str_replace(' ', '', (string) $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    private function formatAmount(float $value): string
    {
        return number_format($value, 2, ',', ' ');
    }

    private function formatPercent(float $value): string
    {
        return number_format($value, 2, ',', ' ') . '%';
    }
}
