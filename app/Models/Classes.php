<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Classes extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'genre', 
        'studios_id', 
        'instructors_id', 
        'description', 
        'availability',
        'time_start', 
        'time_ends', 
        'price',
        'is_active'
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studios_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructors_id');
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'classes_id');
    }
}
