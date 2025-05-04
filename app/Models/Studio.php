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

    public function isEmpty()
    {
        return empty($this->name) &&
               empty($this->address) &&
               empty($this->phone) &&
               empty($this->email) &&
               empty($this->description) &&
               $this->classes()->count() === 0 &&
               $this->instructors()->count() === 0 &&
               $this->images()->count() === 0;
    }

    public function getFormattedAddressAttribute()
    {
        $parts = explode(', ', $this->address);
        $relevantParts = [];
        
        if (!empty($parts[0])) {
            $relevantParts[] = $parts[0];
        }
        
        foreach ($parts as $part) {
            if (str_contains($part, 'zh.k.') || 
                str_contains($part, 'ul.') || 
                str_contains($part, 'blvd.') || 
                $part === $this->city) {
                if (!in_array($part, $relevantParts)) {
                    $relevantParts[] = $part;
                }
            }
        }
        
        return implode(', ', $relevantParts);
    }
}
