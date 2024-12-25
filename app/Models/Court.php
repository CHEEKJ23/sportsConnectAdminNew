<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;
    protected $fillable = [
        'sport_center_id',
        'number',
        'type',
        // 'availability',
    ];

    /**
     * Get the sport center associated with this court.
     */
    public function sportCenter()
    {
        return $this->belongsTo(SportCenter::class, 'sport_center_id');
    }

    /**
     * Get all bookings for this court.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'court_id');
    }

    public function isAvailable($date, $startTime, $endTime)
    {
        return !$this->bookings()
            ->where('date', $date)
            // ->where('status', 'pending') // Only consider confirmed bookings
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('startTime', [$startTime, $endTime])
                      ->orWhereBetween('endTime', [$startTime, $endTime]);
            })->exists();
    }
}
