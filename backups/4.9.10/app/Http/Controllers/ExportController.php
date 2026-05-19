<?php

namespace App\Http\Controllers;

use App\Models\Slide;

use App\Models\PresentationMonth;
use App\Services\Pptx\PptxExporter;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export(Request $request, PresentationMonth $month, PptxExporter $exporter)
    {
        $user = $request->user();

        // Editor может экспортировать только активный месяц
        if (!$user->isAdmin()) {
            if (!$month->is_active) {
                abort(403, 'You can export only active month.');
            }
        }

        $filePath = $exporter->export($month);

        return response()->download($filePath);
    }

    public function exportSlide(Request $request, Slide $slide, PptxExporter $exporter)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->allowedSlides()->where('slides.id', $slide->id)->exists()) {
            abort(403);
        }

        $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

        $filePath = $exporter->exportSingleSlide($activeMonth, $slide);

        return response()->download($filePath);
    }
}
