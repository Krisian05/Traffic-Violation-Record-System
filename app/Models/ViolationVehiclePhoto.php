<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationVehiclePhoto extends Model
{
    protected $fillable = ['violation_id', 'photo'];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
}
