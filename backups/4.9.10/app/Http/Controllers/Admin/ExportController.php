<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresentationMonth;
use App\Services\Pptx\PptxExporter;

class ExportController extends Controller
{
    public function export(PresentationMonth $month, PptxExporter $exporter)
    {
        $filePath = $exporter->export($month);

        return response()->download($filePath);
    }
}
