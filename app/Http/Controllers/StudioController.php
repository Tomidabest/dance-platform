<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\Classes;
use App\Models\Bookings;
use Carbon\Carbon;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::all();
        return view('studios.all-studios', compact('studios'));
    }

    public function single(Studio $studio)
    {
        $studio->load(['instructors.user', 'classes']); 

        $instructors = $studio->instructors;
        
        $classes = $studio->classes;
        return view('studios.single', compact('studio', 'instructors', 'classes'));
    }

    public function book($class_id)
    {
        $class = Classes::findOrFail($class_id);
        $classTime = $class->time_start;

        $timeAvailability = Carbon::parse($classTime)->addHours(5);
        $currTime = Carbon::now();

        echo $timeAvailability;
        echo $currTime;
        
        if($timeAvailability > $currTime)
        {
            $booking = new Bookings();
            $booking->classes_id = $class->id;
            $booking->users_id = 1;
            $booking->date = now();
            $booking->status = 'confirmed';
            $booking->save();

            $class->save();

            return redirect()->route('landing')->with('success', 'Booking confirmed!');
        }
        else{
            return redirect()->route('landing')->with('error', 'No Booking available!');
        }
    }
}