<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function parkingSessions()
    {
        return $this->hasMany(ParkingSession::class);
    }
}
