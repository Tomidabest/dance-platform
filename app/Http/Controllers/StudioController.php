<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;

class StudioController extends Controller
{
    public function index()
    {

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

    }
}