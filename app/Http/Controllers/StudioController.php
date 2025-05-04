<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Studio;
use App\Models\Classes;
use App\Models\Bookings;
use App\Models\Image;
use Carbon\Carbon;

class StudioController extends Controller
{
    public function index(Request $request)
    {
        $studios = Studio::orderBy('created_at', 'desc')->paginate(6);

        if ($request->ajax()) {
            return view('studios.all-studios', compact('studios'))->render();
        }

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

            return redirect()->route('user.dashboard');
        }
        else{
            return redirect()->route('landing');
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
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'city' => 'required|string|max:100',
        ]);

        $studio = Studio::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'description' => $validated['description'] ?? '',
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
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'city' => 'required|string|max:100',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!isset($validated['description'])) {
            $validated['description'] = '';
        }

        $studio->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('images/studios', $filename, 'public');
               
                Image::create([
                    'studios_id' => $studio->id,
                    'img_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.dashboard');
    }

    public function deleteImage($imageId)
    {
        $image = Image::find($imageId);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        if (auth()->user()->studio_id !== $image->studios_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (Storage::disk('public')->exists($image->img_path)) {
            Storage::disk('public')->delete($image->img_path);
        }

        $image->delete();

        return response()->json(['success' => true]);
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