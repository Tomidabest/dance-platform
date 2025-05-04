<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Studio;
use App\Models\Instructor;

class ProfileController extends Controller
{
    public function view()
    {
        $user = Auth::user();
        $studio = null;
        $instructor = null;

        if ($user->role === 'admin') {
            $studio = Studio::find($user->studio_id);
        } elseif ($user->role === 'instructor') {
            $instructor = Instructor::where('users_id', $user->id)->first();
        }

        return view('profile.show', compact('user', 'studio', 'instructor'));
    }

    public function edit()
    {
        $user = Auth::user();
        $instructor = $user->role === 'instructor' ? Instructor::where('users_id', $user->id)->first() : null;

        return view('profile.edit', compact('user', 'instructor'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'experience' => 'nullable|integer|min:0',
            'dance_expertise' => 'nullable|string|max:255',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        if ($user->role === 'instructor') {
            Instructor::updateOrCreate(
                ['users_id' => $user->id],
                [
                    'experience' => $request->experience,
                    'dance_expertise' => $request->dance_expertise,
                ]
            );
        }

        return redirect()->route('profile.view');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();
        $file = $request->file('profile_image');

        if (!$file) {
            return back()->with('error', 'File not detected');
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('images/profiles', $filename, 'public');
        
        if (!$path) {
            return back()->with('error', 'Failed to save image');
        }

        $user->image = 'storage/' . $path;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}

