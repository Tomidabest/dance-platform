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
        'description'
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

    public function firstImage()
    {
        return $this->images()->first() ? $this->images()->first()->img_path : null;
    }
}
