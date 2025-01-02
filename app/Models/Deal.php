<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $primaryKey = 'dealID'; 
    protected $fillable = [
        'userID',
        'title',
        'description',
        'price',
        'location',
        'image_path',
        'status',
        'reason'
    ];

    /**
     * Get the user who created the deal.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }


}
