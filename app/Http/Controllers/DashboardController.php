<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Studio;
use App\Models\Instructor;

class DashboardController extends Controller
{
    public function index()
    {
        
        
        $user = auth()->user();
        $studio = Studio::find($user->studio_id);
        
        
        
        if (!$studio) {
            return redirect()->route('studios.create');
        }
        else{
            $classes = Classes::where('studios_id', $studio->id)
            ->with('instructor')
            ->get();
            $instructors = Instructor::where('studios_id', $studio->id)->get();

            return view('admin.dashboard', compact('studio', 'classes', 'instructors'));
        }
        
    }
}
