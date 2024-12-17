<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'sport_center_id',
        'court_id',
        'date',
        'startTime',
        'endTime',
        'status',
    ];

    /**
     * Get the user who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sport center associated with this booking.
     */
    public function sportCenter()
    {
        return $this->belongsTo(SportCenter::class, 'sport_center_id');
    }

    /**
     * Get the court associated with this booking.
     */
    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
