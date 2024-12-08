<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'equipmentID';

    protected $fillable = [
        'name', 
        'description', 
        'price_per_hour', 
        'quantity_available', 
        'condition', 
        'deposit_amount', 
        'image_path',
        'sport_center_id',
    ];

    public function rentals()
    {
        return $this->hasMany(EquipmentRental::class, 'equipmentID');
    }

    // public function sportCenters()
    // {
    //     // return $this->belongsToMany(SportCenter::class, 'equipment_sport_center', 'equipment_id', 'sport_center_id');
    // }
    // public function sportCenter()
    // {
    //     return $this->belongsTo(SportCenter::class);
    // }
    public function sportCenters()
        {
            return $this->belongsTo(SportCenter::class, 'sport_center_id');
        }
}
