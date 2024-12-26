<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Image extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = 
    [
        'studios_id',
        'img_path'
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studios_id');
    }
}
