<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Studio extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'address', 
        'phone', 
        'email', 
        'description',
        'latitude',
        'longitude',
        'city'
    ];

    public function classes()
    {
        return $this->hasMany(Classes::class, 'studios_id');
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class, 'studios_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'studios_id');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'studios_id');
    }

    public function firstImage()
    {
        return $this->images()->first()?->img_path ?? 'images/studios/placeholder.jpg';
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 50)
    {
        return $query->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) 
            * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) 
            * sin( radians( latitude ) ) ) ) AS distance", 
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance', 'asc');
    }
}
