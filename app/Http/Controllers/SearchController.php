<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->input('Location_Field');
        $genre = $request->input('Genre_Field');
        $date = $request->input('Date_Field');
        $query = Studio::query();

        if (!empty($location)) {
            $query->orWhere('address', 'LIKE', "%{$location}%");
        }

        if (!empty($genre)) {
            $query->orWhereHas('classes', function ($q) use ($genre) {
                $q->Where('genre', 'LIKE', "%{$genre}%");
            });
        }

        if (!empty($date)) {
            $query->orWhereHas('classes', function ($q) use ($date) {
                $q->Where('time_start', 'LIKE', "%{$date}%");
            });
        }

        $studios = $query->paginate(1);
        if (empty($date) && empty($genre) && empty($location)) {
            $studios = collect();
        }
        return view('search_results', compact('studios'));
    }
}
