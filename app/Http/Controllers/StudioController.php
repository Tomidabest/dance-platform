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
        $isAuthenticated = auth()->check();

        $relatedStudios = $this->getRelatedStudios($studio);

        return view('studios.single', compact('studio', 'instructors', 'classes', 'isAuthenticated', 'relatedStudios'));
    }

    public function book($class_id)
    {
        $class = Classes::findOrFail($class_id);
        $classTime = $class->time_start;

        $timeAvailability = Carbon::parse($classTime)->addHours(5);
        $currTime = Carbon::now();

        echo $timeAvailability;
        echo $currTime;
        
        if($timeAvailability > $currTime || $class->availability > 0)
        {
            $booking = new Bookings();
            $booking->classes_id = $class->id;
            $booking->users_id = auth()->id();
            $booking->date = now();
            $booking->status = 'confirmed';
            $booking->save();

            $class->availability -= 1;
            $class->save();

            return redirect()->route('landing')->with('success', 'Booking confirmed!');
        }
        else{
            return redirect()->route('landing')->with('error', 'No Booking available!');
        }
    }

    public function create()
    {
        return view('studios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'city' => 'required|string|max:100',
        ]);

        $studio = Studio::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'description' => $validated['description'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'latitude' => $validated['latitude'],
            'city' => $validated['city'],
            'longitude' => $validated['longitude'],
        ]);

        $user = auth()->user();
        $user->studio_id = $studio->id;
        $user->save();

        return redirect()->route('admin.dashboard');
    }

    public function edit(Studio $studio)
    {
        return view('studios.edit', compact('studio'));
    }

    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'city' => 'required|string|max:100',
        ]);

        $studio->update($validated);

        return redirect()->route('admin.dashboard');
    }

    private function getRelatedStudios(Studio $studio)
    {
        $genres = $studio->classes->pluck('genre')->unique()->filter();

        $query = Studio::query()
            ->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) 
                * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) 
                * sin( radians( latitude ) ) ) ) AS distance", 
                [$studio->latitude, $studio->longitude, $studio->latitude])
            ->where('id', '!=', $studio->id)
            ->where(function ($query) use ($genres, $studio) {
                $query->where('city', $studio->city);
                
                if ($genres->isNotEmpty()) {
                    $query->orWhereHas('classes', function ($q) use ($genres) {
                        $q->whereIn('genre', $genres);
                    });
                }
            })
            ->having('distance', '<', 30);

        if ($genres->isNotEmpty()) {
            $genresString = implode(',', array_map(fn($g) => "'$g'", $genres->toArray()));
            $query->orderByRaw("
                CASE
                    WHEN EXISTS (
                        SELECT 1 FROM classes 
                        WHERE classes.studios_id = studios.id 
                        AND classes.genre IN ({$genresString})
                    ) THEN 1
                    ELSE 2
                END
            ");
        }

        $query->orderBy('distance', 'asc')
            ->limit(6);

        return $query->get();
    }
}