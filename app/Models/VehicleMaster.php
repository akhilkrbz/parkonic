<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaster extends Model
{
    use HasFactory;

    protected $table = 'vehicle_masters';

    protected $fillable = [
        'plate_code',
        'plate_number',
        'emirates',
    ];

    public $timestamps = false;

    public function parkingSessions()
    {
        return $this->hasMany(ParkingSession::class);
    }
}
