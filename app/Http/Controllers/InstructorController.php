<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;

class InstructorController extends Controller
{
    public function show($id)
    {
        $instructor = Instructor::findOrFail($id);
        return view('instructors.show', compact('instructor'));
    }
}
