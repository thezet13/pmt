<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresentationMonth;
use App\Models\Slide;
use App\Models\SlideStatus;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('slide_number')->get();

        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slide_number' => ['required', 'integer', 'min:1', 'unique:slides,slide_number'],
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slide = Slide::create([
            'slide_number' => $data['slide_number'],
            'title' => $data['title'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $months = PresentationMonth::all();

        foreach ($months as $month) {
            SlideStatus::firstOrCreate([
                'presentation_month_id' => $month->id,
                'slide_id' => $slide->id,
            ], [
                'status' => 'in_progress',
            ]);
        }

        return redirect()
            ->route('admin.slides.index')
            ->with('success', 'Slide created.');
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(Request $request, Slide $slide)
    {
        $data = $request->validate([
            'slide_number' => ['required', 'integer', 'min:1', 'unique:slides,slide_number,' . $slide->id],
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slide->update([
            'slide_number' => $data['slide_number'],
            'title' => $data['title'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.slides.index')
            ->with('success', 'Slide updated.');
    }
}
