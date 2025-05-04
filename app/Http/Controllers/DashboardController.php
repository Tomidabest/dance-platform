<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Studio;
use App\Models\Instructor;
use App\Models\Bookings;


class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $user = auth()->user();
        $studio = Studio::find($user->studio_id);

        if (!$studio) {
            return redirect()->route('studios.create');
        } else {
            $classes = Classes::where('studios_id', $studio->id)->with('instructor')->get();
            $instructors = Instructor::where('studios_id', $studio->id)->get();
            $availableInstructors = Instructor::whereNull('studios_id')->with('user')->get();

            return view('admin.dashboard', compact('studio', 'classes', 'instructors', 'availableInstructors'));
        }
    }

    public function userDashboard()
    {
        $user = auth()->user();

        $bookings = Bookings::where('users_id', $user->id)
            ->with(['classes.instructor.user', 'classes.studio'])
            ->get();

        return view('user.dashboard', compact('bookings'));
    }

    public function cancelBooking(Bookings $booking)
    {
        if ($booking->users_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function instructorDashboard()
    {
        $user = auth()->user();
        $instructor = Instructor::where('users_id', $user->id)->first();

        if (!$instructor) {
            return redirect()->route('profile.view')->with('error', 'You are not registered as an instructor.');
        }

        $classes = Classes::where('instructors_id', $instructor->id)
            ->with('studio')
            ->get();

        return view('instructors.dashboard', compact('classes', 'instructor'));
    }
}
