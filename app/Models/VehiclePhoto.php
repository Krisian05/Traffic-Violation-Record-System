<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclePhoto extends Model
{
    protected $fillable = ['vehicle_id', 'photo'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
