<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Instructor;
use App\Models\Bookings;

class ClassController extends Controller
{
    public function create()
    {
        $instructors = Instructor::where('studios_id', auth()->user()->studio_id)
        ->with('user')
        ->get();
        return view('admin.class.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'instructor_id' => 'nullable|exists:instructors,id',
            'availability' => 'required|integer|min:0',
            'time_start' => 'required|date_format:Y-m-d\TH:i',
            'time_ends' => 'required|date_format:Y-m-d\TH:i|after:time_start',
            'price' => 'required|numeric|min:0',
        ]);

        Classes::create( 
            [
                'name' => $validated['name'],
                'genre' => $validated['genre'],
                'description' => $validated['description'],
                'instructors_id' => $validated['instructor_id'],
                'studios_id' => auth()->user()->studio_id,
                'availability' => $validated['availability'],
                'time_start' => $validated['time_start'],
                'time_ends' => $validated['time_ends'],
                'price' => $validated['price'],
            ]
        );

        

        return redirect()->route('admin.dashboard')->with('success', 'Class created successfully.');
    }

    public function update(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'instructor_id' => 'nullable|exists:instructors,id',
            'availability' => 'required|integer|min:0',
            'time_start' => 'required|date_format:Y-m-d\TH:i',
            'time_ends' => 'required|date_format:Y-m-d\TH:i|after:time_start',
            'price' => 'required|numeric|min:0',
        ]);

        if ($class->studios_id !== auth()->user()->studio_id) {
            abort(403, 'Unauthorized');
        }

        $class->update([
            'name' => $validated['name'],
            'genre' => $validated['genre'],
            'description' => $validated['description'],
            'instructor_id' => $validated['instructor_id'],
            'availability' => $validated['availability'],
            'time_start' => $validated['time_start'],
            'time_ends' => $validated['time_ends'],
            'price' => $validated['price'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Class updated successfully.');
    }


    public function edit(Classes $class)
    {
        if ($class->studios_id !== auth()->user()->studio_id) {
            abort(403, 'Unauthorized');
        }

        $instructors = Instructor::where('studios_id', auth()->user()->studio_id)->with('user')->get();
        return view('admin.class.edit', compact('class', 'instructors'));
    }


    public function destroy(Classes $class)
    {
        if ($class->studios_id !== auth()->user()->studio_id) {
            abort(403, 'Unauthorized');
        }

        $class->delete();

        return redirect()->route('dashboard')->with('success', 'Class deleted successfully.');
    }

    public function toggleStatus(Classes $class)
    {
        if ($class->studios_id !== auth()->user()->studio_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
    
        // Toggle status and save
        $class->is_active = !$class->is_active;
        $class->save();
    
        return response()->json([
            'success' => true,
            'is_active' => $class->is_active,
            'message' => 'Class status updated successfully.'
        ]);
    }

    public function assignInstructor(Request $request, Classes $class)
    {
        if ($class->studios_id !== auth()->user()->studio_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
        ]);

        $class->update(['instructor_id' => $validated['instructor_id']]);

        return back()->with('success', 'Instructor assigned successfully.');
    }

    public function showBookings(Classes $class)
    {
        if ($class->studios_id !== auth()->user()->studio_id) {
            abort(403, 'Unauthorized');
        }

        $bookings = $class->bookings()->with('user')->get();
        return view('admin.class.bookings', compact('class', 'bookings'));
    }

    public function destroyBooking(Bookings $booking)
    {
        if ($booking->classes->studios_id !== auth()->user()->studio_id) {
            abort(403, 'Unauthorized');
        }

        $booking->delete();

        return back()->with('success', 'Booking removed successfully.');
    }
}
