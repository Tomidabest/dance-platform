<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Instructor;

class InstructorController extends Controller
{
    public function show($id)
    {
        $instructor = Instructor::findOrFail($id);
        return view('instructors.show', compact('instructor'));
    }

    public function edit()
    {
        $instructor = Instructor::where('users_id', Auth::id())->firstOrFail();
        return view('instructors.edit', compact('instructor'));
    }

    public function update(Request $request)
    {
        $instructor = Instructor::where('users_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'experience' => 'nullable|integer|min:0',
            'dance_expertise' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($instructor->image) {
                Storage::delete('public/' . $instructor->image);
            }
            $imagePath = $request->file('image')->store('images/instructors', 'public');
            $validated['image'] = $imagePath;
        }

        $instructor->update($validated);

        return redirect()->route('profile');
    }

    public function setup($id)
    {
        $instructor = Instructor::findOrFail($id);

        if ($instructor->users_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('instructors.edit', compact('instructor'));
    }

    public function storeSetup(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);

        if ($instructor->users_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'experience' => 'required|integer|min:0',
            'dance_expertise' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $instructor->update($validated);

        return redirect()->route('profile.view');
    }

    public function assignToStudio(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
        ]);

        $instructor = Instructor::findOrFail($validated['instructor_id']);
        $user = auth()->user();

        $instructor->studios_id = $user->studio_id;
        $instructor->save();

        return redirect()->route('admin.dashboard');
    }
}
