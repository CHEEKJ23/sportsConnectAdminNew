<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'points_needed', 'image_path'];

    public function redemptions()
    {
        return $this->hasMany(RedemptionRequest::class, 'gift_id');
    }
}
