<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Instructor extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'experience', 
        'dance_expertise', 
        'users_id', 
        'studios_id',
        'description',
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studios_id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'instructors_id');
    }
}
