<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresentationMonth;
use App\Models\Slide;
use App\Models\SlideStatus;
use Illuminate\Http\Request;

class PresentationMonthController extends Controller
{
    public function index()
    {
        $months = PresentationMonth::orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('admin.months.index', compact('months'));
    }

    public function create()
    {
        return view('admin.months.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $presentationMonth = PresentationMonth::create([
            'name' => $data['name'],
            'year' => $data['year'],
            'month' => $data['month'],
            'is_active' => false,
        ]);

        $slides = Slide::where('is_active', true)->get();

        foreach ($slides as $slide) {
            SlideStatus::firstOrCreate([
                'presentation_month_id' => $presentationMonth->id,
                'slide_id' => $slide->id,
            ], [
                'status' => 'in_progress',
            ]);
        }

        return redirect()
            ->route('admin.months.index')
            ->with('success', 'Month created.');
    }

    public function activate(PresentationMonth $month)
    {
        PresentationMonth::query()->update(['is_active' => false]);

        $month->update(['is_active' => true]);

        $slides = Slide::where('is_active', true)->get();

        foreach ($slides as $slide) {
            SlideStatus::firstOrCreate([
                'presentation_month_id' => $month->id,
                'slide_id' => $slide->id,
            ], [
                'status' => 'in_progress',
            ]);
        }

        return redirect()
            ->route('admin.months.index')
            ->with('success', 'Month activated.');
    }
}
