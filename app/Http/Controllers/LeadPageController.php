<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;

class LeadPageController extends Controller
{
    public function index()
    {
        $studios = Studio::where('featured', true)->get();

        return view('landing', compact('studios'));
    }
}
