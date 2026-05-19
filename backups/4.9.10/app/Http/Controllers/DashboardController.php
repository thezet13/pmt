<?php

namespace App\Http\Controllers;

use App\Models\PresentationMonth;
use App\Models\Slide;
use App\Models\SlideStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $activeMonth = PresentationMonth::where('is_active', true)->first();

        if ($user->isAdmin()) {
            $slides = Slide::where('is_active', true)
                ->orderBy('slide_number')
                ->get();
        } else {
            $slides = $user->allowedSlides()
                ->where('is_active', true)
                ->orderBy('slide_number')
                ->get();
        }

        $statuses = collect();

        if ($activeMonth) {
            $statuses = SlideStatus::where('presentation_month_id', $activeMonth->id)
                ->get()
                ->keyBy('slide_id');
        }

        return view('dashboard', [
            'user' => $user,
            'activeMonth' => $activeMonth,
            'slides' => $slides,
            'statuses' => $statuses,
        ]);
    }
}
