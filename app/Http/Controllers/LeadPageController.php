<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadPageController extends Controller
{
    public function index()
    {
        return view('landing');
    }
}
