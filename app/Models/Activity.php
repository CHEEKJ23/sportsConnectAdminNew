<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sportType',
        'sport_center_id',
        'date',
        'startTime',
        'endTime',
        'player_quantity',
        'price_per_pax',
        'status',
    ];

    // Define relationship with users (activity creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define relationship with players
    public function players()
    {
        return $this->belongsToMany(User::class, 'activity_user')->withPivot('player_quantity');
    }

    // Define relationship with the sport center
    public function sportCenter()
    {
        return $this->belongsTo(SportCenter::class);
    }
}
