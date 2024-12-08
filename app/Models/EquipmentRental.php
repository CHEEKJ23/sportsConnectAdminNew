<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRental extends Model
{
    use HasFactory;
    protected $primaryKey = 'rentalID';

    protected $fillable = [
        'userID', 
        'equipmentID', 
        'sport_center_id', 
        'date', 
        'startTime', 
        'endTime', 
        'quantity_rented', 
        'deposit_returned', 
        'rentalStatus',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipmentID');
    }

    public function sportCenter()
    {
        return $this->belongsTo(SportCenter::class, 'sport_center_id');
        
    }

    public function user()
    {
        // return $this->belongsTo(User::class, 'userID');
        return $this->belongsTo(User::class);


    }
}