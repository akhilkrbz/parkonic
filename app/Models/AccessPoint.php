<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_exit',
    ];

    public $timestamps = false;

    public function entryParkingSessions()
    {
        return $this->hasMany(ParkingSession::class, 'entry_access_point_id');
    }

    public function exitParkingSessions()
    {
        return $this->hasMany(ParkingSession::class, 'exit_access_point_id');
    }
}
