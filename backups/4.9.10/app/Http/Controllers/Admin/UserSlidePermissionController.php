<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\SlidePermission;
use App\Models\User;
use Illuminate\Http\Request;

class UserSlidePermissionController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.permissions.index', compact('users'));
    }

    public function edit(User $user)
    {
        $slides = Slide::where('is_active', true)
            ->orderBy('slide_number')
            ->get();

        $allowedSlideIds = $user->allowedSlides()
            ->pluck('slides.id')
            ->toArray();

        return view('admin.permissions.edit', compact(
            'user',
            'slides',
            'allowedSlideIds'
        ));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'slides' => ['nullable', 'array'],
            'slides.*' => ['integer', 'exists:slides,id'],
        ]);

        SlidePermission::where('user_id', $user->id)->delete();

        foreach ($data['slides'] ?? [] as $slideId) {
            SlidePermission::create([
                'user_id' => $user->id,
                'slide_id' => $slideId,
            ]);
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permissions updated.');
    }
}
