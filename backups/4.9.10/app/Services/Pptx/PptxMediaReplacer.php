<?php

namespace App\Services\Pptx;

use RuntimeException;

class PptxMediaReplacer
{
    public function replaceImageByShapeName(
        string $tmpDir,
        int $slideXmlNumber,
        string $shapeName,
        string $newImagePath
    ): void {
        $slideXmlPath = $tmpDir . "/ppt/slides/slide{$slideXmlNumber}.xml";
        $relsPath = $tmpDir . "/ppt/slides/_rels/slide{$slideXmlNumber}.xml.rels";

        if (!file_exists($slideXmlPath)) {
            throw new RuntimeException("Slide XML not found: {$slideXmlPath}");
        }

        if (!file_exists($relsPath)) {
            throw new RuntimeException("Slide rels not found: {$relsPath}");
        }

        if (!file_exists($newImagePath)) {
            throw new RuntimeException("New image not found: {$newImagePath}");
        }

        $xml = file_get_contents($slideXmlPath);

        $pattern = '/<p:pic\b.*?<p:cNvPr\b[^>]*name="' . preg_quote($shapeName, '/') . '"[^>]*>.*?<a:blip\b[^>]*r:embed="([^"]+)".*?<\/p:pic>/s';

        if (!preg_match($pattern, $xml, $matches)) {
            throw new RuntimeException("Picture shape not found: {$shapeName}");
        }

        $rId = $matches[1];

        $relsXml = file_get_contents($relsPath);

        $relPattern = '/<Relationship\b[^>]*Id="' . preg_quote($rId, '/') . '"[^>]*Target="([^"]+)"/';

        if (!preg_match($relPattern, $relsXml, $relMatches)) {
            throw new RuntimeException("Relationship not found for {$rId}");
        }

        $target = $relMatches[1];

        $mediaFile = basename($target);
        $mediaPath = $tmpDir . '/ppt/media/' . $mediaFile;

        if (!file_exists($mediaPath)) {
            throw new RuntimeException("Media file not found: {$mediaPath}");
        }

        copy($newImagePath, $mediaPath);
    }
}
