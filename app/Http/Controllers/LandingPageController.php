<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;

class LandingPageController extends Controller
{
    public function index()
    {
        $featuredStudios = Studio::where('featured', true)->get();

        return view('landing', compact('featuredStudios'));
    }
}
