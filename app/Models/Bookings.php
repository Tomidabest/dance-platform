<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Bookings extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'classes_id',
        'user_id', 
        'date', 
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'classes_id');
    }
}
