<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCenter extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'location',
        'image',
        'price',
    ];

    /**
     * Get all courts in this sports center.
     */
    public function courts()
    {
        return $this->hasMany(Court::class, 'sport_center_id');
    }

    /**
     * Get all bookings made in this sports center.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'sport_center_id');
    }
    
/**
     * Define relationship: SportCenter has many equipment rentals.
     */
    
    public function equipmentRentals()
    {
        return $this->hasMany(EquipmentRental::class, 'sport_center_id');
    }
/**
     * Define relationship: SportCenter has many equipment items.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'sport_center_id');
    }
}
