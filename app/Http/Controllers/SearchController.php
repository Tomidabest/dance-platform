<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $address = $request->input('address');
        $genre = $request->input('genre');
        $date = $request->input('date');

        $query = Studio::query();

        if (!empty($address)) {
            $query->where('address', 'LIKE', "%{$address}%");
        }

        if (!empty($genre)) {
            $query->whereHas('classes', function ($q) use ($genre) {
                $q->where('genre', 'LIKE', "%{$genre}%")
                  ->where('is_active', true);
            });
        }

        if (!empty($date)) {
            $query->whereHas('classes', function ($q) use ($date) {
                $q->whereDate('time_start', '=', $date)
                  ->where('is_active', true);
            });
        }

        $studios = $query->paginate(6);

        if (empty($date) && empty($genre) && empty($address)) {
            return view('search_results')->with('studios', collect());
        }

        return view('search_results', compact('studios'));
    }
}
