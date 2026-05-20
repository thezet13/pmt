<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PresentationMonthController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserSlidePermissionController;
use App\Http\Controllers\SlideEditorController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;

use App\Models\PresentationMonth;
use App\Models\Slide;
use App\Models\SlideValue;
use App\Services\Charts\MultiDonutChartSvg;
use App\Services\Charts\Slide8DiagramImageService;
use App\Services\Charts\Slide9DonutImageService;
use App\Services\Charts\Slide13DonutSvg;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/slides/{slide}/edit', [SlideEditorController::class, 'edit'])
        ->name('slides.edit');

    Route::post('/reports/{month}/export', [ExportController::class, 'export'])
        ->name('reports.export');

    Route::put('/slides/{slide}', [SlideEditorController::class, 'update'])
        ->name('slides.update');

    Route::post('/slides/{slide}/export', [ExportController::class, 'exportSlide'])
        ->name('slides.export');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/months', [PresentationMonthController::class, 'index'])
            ->name('months.index');

        Route::get('/months/create', [PresentationMonthController::class, 'create'])
            ->name('months.create');

        Route::post('/months', [PresentationMonthController::class, 'store'])
            ->name('months.store');

        Route::post('/months/{month}/activate', [PresentationMonthController::class, 'activate'])
            ->name('months.activate');

        Route::get('/slides', [SlideController::class, 'index'])
            ->name('slides.index');

        Route::get('/slides/create', [SlideController::class, 'create'])
            ->name('slides.create');

        Route::post('/slides', [SlideController::class, 'store'])
            ->name('slides.store');

        Route::get('/slides/{slide}/edit', [SlideController::class, 'edit'])
            ->name('slides.edit');

        Route::put('/slides/{slide}', [SlideController::class, 'update'])
            ->name('slides.update');

        Route::get('/permissions', [UserSlidePermissionController::class, 'index'])
            ->name('permissions.index');

        Route::get('/permissions/{user}/edit', [UserSlidePermissionController::class, 'edit'])
            ->name('permissions.edit');

        Route::put('/permissions/{user}', [UserSlidePermissionController::class, 'update'])
            ->name('permissions.update');

        Route::post('/months/{month}/export', [AdminExportController::class, 'export'])
            ->name('months.export');
    });


Route::get('/test-multi-donut', function (MultiDonutChartSvg $chart) {

    $svg = $chart->render([
        'legend' => [
            ['label' => 'Sosial müdafiə', 'color' => '#10A8D8'],
            ['label' => 'Əlillik', 'color' => '#BDEEFF'],
            ['label' => 'Məşğulluq', 'color' => '#21BF73'],
            ['label' => 'VƏM', 'color' => '#0047BA'],
            ['label' => 'Əmək münasibətləri', 'color' => '#2C75FF'],
            ['label' => 'Arayışların verilməsi', 'color' => '#F4C542'],
        ],
        'charts' => [
            [
                'center_label' => '2024',
                'center_value' => '726 361',
                'values' => [62.7, 0.1, 27.6, 5.8, 1.9, 1.9],
            ],
            [
                'center_label' => '2025',
                'center_value' => '126 991',
                'values' => [60.1, 0.1, 20.6, 16.4, 1.8, 1.0],
            ],
            [
                'center_label' => 'Ümumi',
                'center_value' => '2 824 592',
                'values' => [61.4, 0.8, 23.8, 9.5, 1.2, 3.3],
            ],
        ],
    ]);

    return response($svg, 200)->header('Content-Type', 'image/svg+xml');
});


Route::get('/slide-8-diagram', function (Slide8DiagramImageService $service) {
    $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

    $slide = Slide::where('slide_number', 8)->firstOrFail();

    $slideValue = SlideValue::where('presentation_month_id', $activeMonth->id)
        ->where('slide_id', $slide->id)
        ->firstOrFail();

    $values = $slideValue->values_json ?? [];

    $diagramBlock = $values['diagram_block'] ?? null;

    if (!is_array($diagramBlock)) {
        abort(404, 'diagram_block not found for slide 8.');
    }

    $svg = $service->renderSvg($diagramBlock);

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
});



Route::get('/slide-9-donut', function (
    \App\Services\Charts\Slide9DonutImageService $chart
) {
    $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

    $slide = Slide::where('slide_number', 9)->firstOrFail();

    $slideValue = SlideValue::where('presentation_month_id', $activeMonth->id)
        ->where('slide_id', $slide->id)
        ->firstOrFail();

    $values = $slideValue->values_json ?? [];

    $donutBlock = $values['donut_block'] ?? null;

    if (!is_array($donutBlock)) {
        abort(404, 'donut_block not found for slide 9.');
    }

    $svg = $chart->renderSvg($donutBlock);

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
});

Route::get('/slide-10-donut', function (MultiDonutChartSvg $chart) {
    $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

    $slide = Slide::where('slide_number', 10)->firstOrFail();

    $slideValue = SlideValue::where('presentation_month_id', $activeMonth->id)
        ->where('slide_id', $slide->id)
        ->firstOrFail();

    $values = $slideValue->values_json ?? [];

    $donutBlock = $values['donut_block'] ?? null;

    if (!is_array($donutBlock)) {
        abort(404, 'donut_block not found for slide 10.');
    }

    $svg = $chart->render($donutBlock);

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
});

Route::get('/slide-13-donut', function (Slide13DonutSvg $chart) {
    $activeMonth = PresentationMonth::where('is_active', true)->firstOrFail();

    $slide = Slide::where('slide_number', 13)->firstOrFail();

    $slideValue = SlideValue::where('presentation_month_id', $activeMonth->id)
        ->where('slide_id', $slide->id)
        ->firstOrFail();

    $values = $slideValue->values_json ?? [];

    $donutCert = $values['donut_cert'] ?? null;

    if (!is_array($donutCert)) {
        abort(404, 'donut_cert not found for slide 13.');
    }

    $svg = $chart->render($donutCert);

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
});


Route::get('/debug-php', function () {
    return response()->json([
        'php' => PHP_VERSION,
        'zip_loaded' => extension_loaded('zip'),
        'ziparchive_exists' => class_exists(\ZipArchive::class),
        'loaded_extensions' => get_loaded_extensions(),
    ]);
});

require __DIR__ . '/auth.php';
