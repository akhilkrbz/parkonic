<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSession extends Model
{
    use HasFactory;

    protected $table = 'parking_sessions';

    protected $fillable = [
        'location_id',
        'building_id',
        'entry_access_point_id',
        'exit_access_point_id',
        'vehicle_master_id',
        'in_time',
        'out_time',
        'status',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function entryAccessPoint()
    {
        return $this->belongsTo(AccessPoint::class, 'entry_access_point_id');
    }

    public function exitAccessPoint()
    {
        return $this->belongsTo(AccessPoint::class, 'exit_access_point_id');
    }

    public function vehicleMaster()
    {
        return $this->belongsTo(VehicleMaster::class);
    }

    /**
     * Casts
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'in_time' => 'datetime',
            'out_time' => 'datetime',
        ];
    }
}
